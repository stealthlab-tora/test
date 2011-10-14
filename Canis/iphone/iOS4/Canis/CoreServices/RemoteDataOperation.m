//
//  RemoteDataOperation.m
//  Ubira
//
//  Created by [Cybage Team] on 10/05/11.
//  Copyright 2011 FreeCause All rights reserved.
//

#import "RemoteDataOperation.h"
#import "NetworkManager.h"

/*!
 @class         RemoteDataOperation (Private)
 @abstract      This category is to create and private method for the RemoteDataOperation.  
 @discussion    This category will extend the functionality of the RemoteDataOperation.
 */
@interface RemoteDataOperation (Private)
- (void)done;
@end

@implementation RemoteDataOperation (Private)

/*!
 @function      done
 @abstract      private method to change the status of operation
 @discussion    This method is just for convenience. It cancels the URL connection 
                if it still exists and finishes up the operation.
 @param         void
 @result        void
 */
- (void)done
{
	@try {
        //Cancel and release the connection
		[_remoteConnection cancel];
		[_remoteConnection release];
		_remoteConnection = nil;
		
		//Release from active connections
        [[NetworkManager sharedManager] releaseFromActiveConnections];
		
		//Alert observers that connection is finished using KVO
		[self willChangeValueForKey:@"isExecuting"];
		[self willChangeValueForKey:@"isFinished"];
		executing_ = NO;
		finished_  = YES;
		[self didChangeValueForKey:@"isFinished"];
		[self didChangeValueForKey:@"isExecuting"];
	}
	@catch (NSException * exception) {
         TRC_EXCEPTION(exception); 
	}	
}

@end

@implementation RemoteDataOperation

@synthesize remoteUrlRequest    = _remoteUrlRequest;
@synthesize remoteConnection    = _remoteConnection;
@synthesize remoteData          = _remoteData;
@synthesize error               = _connectionError;
@synthesize contentSize;

#pragma mark -
#pragma mark Initialization & Memory Management
- (id)init {
	return [self initWithUrlRequest:nil ];
}

/*!
 @function      initWithUrlRequest
 @abstract      initialize the url request object and take the ownership for the same.
 @discussion    initialize the url request object and take the ownership for the same.
 @param         NSURLRequest
 @result        return the RemoteDataOperation object
 */
- (id)initWithUrlRequest:(NSURLRequest*)requestUrl 
{
	if( (self = [super init]) ) {
        self.remoteUrlRequest = requestUrl;
        _remoteData = nil;
        _connectionError = nil;
  	}
	return self;
}

- (void)dealloc
{
	[_remoteConnection cancel]; 
	[_remoteConnection release]; 
	
	[_connectionError release];
	[_remoteUrlRequest release];
	
	[_remoteData release];
	[super dealloc];
}

#pragma mark -
#pragma mark Start & Utility Methods
/*!
 @function      start
 @abstract      initialize the url request.
 @discussion    initialize the url request and add it to run loop and start the operation.
 @param         void
 @result        void
 */
- (void)start
{
	@try
	{
		// Ensure this operation is not being restarted and that it has not been cancelled
		if( finished_ || [self isCancelled] ) { [self done]; return; }
		
		// From this point on, the operation is officially executing--remember, isExecuting
		// needs to be KVO compliant!
		[self willChangeValueForKey:@"isExecuting"];
		executing_ = YES;
		[self didChangeValueForKey:@"isExecuting"];
        
		// Create the NSURLConnection--this could have been done in init, but we delayed 
		// until this operation is started by queue. This prevents the connection to be established in case the operation
        // was never enqueued or was cancelled before starting
		_remoteConnection = [[NSURLConnection alloc] initWithRequest:_remoteUrlRequest delegate:self startImmediately:NO];
		
        //Add this connection to active connections
        [[NetworkManager sharedManager] addToActiveConnections];
        
        //Schecule the connection to run in the main runloop
		[self.remoteConnection scheduleInRunLoop:[NSRunLoop mainRunLoop] forMode:NSDefaultRunLoopMode];
		
		[self.remoteConnection start];	
	}
	@catch (NSException * exception) {
         TRC_EXCEPTION(exception);
	}
}

#pragma mark -
#pragma mark Overrides
/*!
 @function      isConcurrent
 @abstract      operation is concurrent.
 @discussion    operation is concurrent.
 @param         void
 @result        BOOL
 */
- (BOOL)isConcurrent
{
	return YES;
}

/*!
 @function      isExecuting
 @abstract      return the current status of operation.
 @discussion    operation is currently execution.
 @param         void
 @result        BOOL
 */
- (BOOL)isExecuting
{
	return executing_;
}

/*!
 @function      isFinished
 @abstract      return the finish status of operation.
 @discussion    operation is finished execution.
 @param         void
 @result        BOOL
 */
- (BOOL)isFinished
{
	return finished_;
}

/*!
 @function      cancel
 @abstract      cancel the current operation.
 @discussion    cancel the current operation and set the status as well
 @param         void
 @result        void
 */
- (void)cancel
{
	[super cancel];
	[self done];
}

#pragma mark -
#pragma mark Delegate Methods for NSURLConnection

//When data starts streaming in from the web service, the connection:didReceiveResponse: method will be called 
- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response 
{
    @try {
        NSHTTPURLResponse* httpResponse = (NSHTTPURLResponse*)response;
        NSInteger statusCode = [httpResponse statusCode];
        
        //Lazly allocate the required buffer from http response 
        if( statusCode == 200 ) {
            
            contentSize = [httpResponse expectedContentLength] > 0 ? [httpResponse expectedContentLength] : 0;
            [_remoteData release];
            _remoteData = [[NSMutableData alloc] initWithCapacity:contentSize];
        } 
        else
        {
            //Retain the error object for further assesment by caller
            [_connectionError release];
            _connectionError = nil;
            
            NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:[NSHTTPURLResponse localizedStringForStatusCode:statusCode], kError, nil];
            
            NSError *error = [NSError errorWithDomain:@"Ubira" code:kNoNetworkErr userInfo:userInfo];
            _connectionError = [error retain];
            
            [userInfo release];
            [self done];
        }      
	}
	@catch (NSException * exception) {
         TRC_EXCEPTION(exception);
	}	
}

//As the data progressively comes in from the web service, the connection:didReceiveData: method will be called repeated
//use this method to append the data received to the webData object:
- (void)connection:(NSURLConnection *)connection 
	didReceiveData:(NSData *) data 
{
	@synchronized(self)
	{
		if( data == nil )
		{
			_remoteData = nil;
		}
		else {
			[_remoteData appendData:data];
		}
	}

}

//If there is an error during the transmission, the connection:didFailWithError: method will be called:
- (void)connection:(NSURLConnection *)connection 
  didFailWithError:(NSError *)error 
{
	@try {
        //Retain the error object for further assesment by caller
		[_connectionError release];
        _connectionError = nil;
        
        _connectionError = [error retain];
        
        //Release half cooked data
        [_remoteData release];
        _remoteData = nil;
        
        //Close remote connection and update KVO
		[self done];
        
	} @catch (NSException * exception) {
        TRC_EXCEPTION(exception); 	
	}
}

//When the connection has finished and succeeded in downloading the response, 
//the connectionDidFinishLoading: method will be called:
- (void)connectionDidFinishLoading:(NSURLConnection *)connection 
{
	[self done];  
}

- (void)connection:(NSURLConnection *)connection didReceiveAuthenticationChallenge:(NSURLAuthenticationChallenge *)challenge
{
    @try {
        //Received authentication challenge
        [[challenge sender] cancelAuthenticationChallenge:challenge];
        [_remoteData release];
        _remoteData = nil;
        [self done];        
	}
	@catch (NSException * exception) {
        TRC_EXCEPTION(exception); 
	}
}

@end
