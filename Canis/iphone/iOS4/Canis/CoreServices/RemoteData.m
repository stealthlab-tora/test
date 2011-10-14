//
//  RemoteData.m
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "RemoteData.h"
#import "NetworkManager.h"

/*!
 @class         RemoteData (Private)
 @abstract      This category is to create and private method for the RemoteDataOperation.  
 @discussion    This category will extend the functionality of the RemoteDataOperation.
 */
@interface RemoteData (Private)
- (void)done;
@end

@implementation RemoteData (Private)

/*!
 @function      done
 @abstract      private method to change the status of operation
 @discussion    This method is just for convenience. It cancels the URL connection if it
                still exists and finishes up the operation.
 @param         void
 @result        void
 */
- (void)done
{
	@try {
        
        //Cancel and release the connection
		[_connection cancel];
		[_connection release];
		_connection = nil;
		
		//Release from active connections
        [[NetworkManager sharedManager] releaseFromActiveConnections];		
				
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}	
}

@end

@implementation RemoteData

@synthesize connection = _connection, downloadedData = _downloadedData;
@synthesize delegate = _delegate, urlRequest =_urlRequest;


- (void)dealloc
{
    TRC_DBG(@"RemoteData --- Release");
	_delegate = nil;
	[_connection release];
    _connection = nil;
	[_downloadedData release];
    _downloadedData = nil;
	[_urlRequest release];
    _urlRequest = nil;
	[super dealloc];
}

/*!
 @function      initWithUrlRequest
 @abstract      initialise the request object and take the ownership of request
 @discussion    nitialise the request object and take the ownership of request and make
                the request call.
 @param         requestUrl
 @param         remoteDelegate.
 @return        void
 */
- (void)initWithUrlRequest:(NSURLRequest*)requestUrl delegate:(id)remoteDelegate
{
	self.delegate = remoteDelegate;
	[self makeRequest:requestUrl];
}

/*!
 @function      makeRequest
 @abstract      initiat server request
 @discussion    initiate the server request if network is available else return and error.
 @param         requestUrl
 @param         remoteDelegate.
 @return        void
 */
- (void)makeRequest:(NSURLRequest*)requestUrl
{
    @try {
        if([[NetworkManager sharedManager] isReachable]){
            self.urlRequest = requestUrl;
            
            if( _connection )
            {
                //cancel any pending connection 
                [self done];
            }
            //set 10 sec timeout if server not responding 
            NSURL *url =[NSURL URLWithString:[NSString	stringWithFormat:@"%@/signin",kCanisServerUrl]];
            if ([[requestUrl URL] isEqual:url])
            {
                requestTimer = [NSTimer scheduledTimerWithTimeInterval:10.0 target:self selector:@selector(connectionTimeOut) userInfo:nil repeats:NO];
            }
            [[NetworkManager sharedManager] addToActiveConnections];
            _connection = [[NSURLConnection alloc] initWithRequest:self.urlRequest delegate:self];
        }
        else 
        {
            NSError *error = nil;//set No network connectivity in object.		
            NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:kNoNetwork,kError, nil];
            error = [NSError errorWithDomain:@"Ubira" code:kNoNetworkErr userInfo:userInfo];
            [userInfo release];
            
            if([self.delegate respondsToSelector:@selector(handleError:)])
            {
                [self.delegate handleError:error]; //No Network connection
            }
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}
	
}

#pragma mark NSURLConnection delegate methods
- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)response
{
    @try {
        [requestTimer invalidate];
        requestTimer = nil;
        NSHTTPURLResponse* httpResponse = (NSHTTPURLResponse*)response;
        NSInteger statusCode = [httpResponse statusCode];
        TRC_DBG(@"%d",statusCode);
        //Lazly allocate the required buffer from http response 
        if( statusCode == 200 ) {
            
            int contentSize = [httpResponse expectedContentLength] > 0 ? [httpResponse expectedContentLength] : 0;
            [_downloadedData release];
            _downloadedData = nil;
            _downloadedData = [[NSMutableData alloc] initWithCapacity:contentSize];
        } 
        else
        {
            [self done];
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}
    
}

- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data
{
    [self.downloadedData appendData:data]; 
}

- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error
{
    @try {
        [self.downloadedData release];
        _downloadedData = nil;
        [self done];
        if([self.delegate respondsToSelector:@selector(handleError:)])
        {
            [self.delegate handleError:error];
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}
}

- (void)connectionDidFinishLoading:(NSURLConnection *)connection
{
    @try {
        [self done];
        
        if([self.delegate respondsToSelector:@selector(handleReceivedData:)])
        {
            
            [self.delegate handleReceivedData:self.downloadedData];
        }       
	}
	@catch (NSException * exception) {
         TRC_EXCEPTION(exception); 
	}
	
}

/*!
 @function      connectionTimeOut
 @abstract      denied log in request if server is not responding whithin 10 sec. 
 @discussion    denied log in request if server is not responding whithin 10 sec.
 @return        void
 */
- (void)connectionTimeOut
{
    @try {
        [self done];
        
        NSError *error = nil;//set No network connectivity in object.		
        NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:kServerError,kError, nil];
        error = [NSError errorWithDomain:@"Ubira" code:kNoServerResponce userInfo:userInfo];
        [userInfo release];
        
        if([self.delegate respondsToSelector:@selector(handleError:)])
        {
            [self.delegate handleError:error];
        }      
	}
	@catch (NSException * exception) {
         TRC_EXCEPTION(exception);
	}
}

@end