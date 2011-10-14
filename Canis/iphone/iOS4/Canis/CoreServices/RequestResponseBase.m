//
//  RequestResponseBase.m
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "RequestResponseBase.h"

@implementation RequestResponseBase

@synthesize webService,  delegate =_delegate;

/*!
 @function      init
 @abstract      initialise the remote data
 @discussion    initialise the remote data to handle the asynchronous server handling.
 @param         void
 @result        void
 */
- (id)init
{
	if((self = [super init]))
	{
		webService = [[RemoteData alloc]init];
		[webService setDelegate:self];
		return self;
	}
	return nil;
}

/*!
 @function      createRequest
 @abstract      abstract implementation
 @discussion    abstract method implemented by the child classes.
 @param         void
 @result        void
 */
- (void)createRequest
{
	//Create NSUrl and make request
}

- (void)dealloc
{
	_delegate = nil;
    webService.delegate = nil;
	[webService release];
	[super dealloc];
}

#pragma mark RemoteDataDelegate delegate methods
/*!
 @function      handleReceivedData
 @abstract      delegate implementation
 @discussion    RemoteDataDelegate method to handle the server response and ask parser to
                parse the data.
 @param         void
 @result        return YES
 */
- (BOOL)checkForErrors:(NSData*)data
{
    BOOL retValue = FALSE;
    //Check for data Error
	NSString* responseString = [[NSString alloc] initWithData:data encoding:NSASCIIStringEncoding];    
    
    NSError *error = nil;
    
    SBJsonParser *jsonParser = [SBJsonParser new];
    id repr = [jsonParser objectWithString:responseString];
    [responseString release];
    @try
    {
        if (!repr)    {
            //This error object is get filled when data si not in valid JSON format. We can check trace byfollowing erroTrace statement which is currentlty commented.
            // NSLog(@"-JSONValue failed. Error trace is: %@", [jsonParser errorTrace]);
            
            //This type of error occres only when server returns any HTML data. eg. Data base is down. 
            NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:kServerError,kError, nil];
            error = [NSError errorWithDomain:@"Ubira" code:kNoUserExist userInfo:userInfo];
            [userInfo release];
        }
        [jsonParser release];
        
        if (!error) {    
            
            NSDictionary *resultDictionary = (NSDictionary*)repr;        
            //Here we checking the Error tag in valid JSON data if it is present then we fill error object.
            //otherwise error object will be nil.
            NSString* errorString = [resultDictionary objectForKey:kError];    
            if (errorString == nil) {            
                //No Data Error
                retValue = TRUE;
            }else {
                
                //Data Error occured		
                if([resultDictionary valueForKey:kError])
                {        
                    NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:[resultDictionary valueForKey:kError],kError, nil];
                    error = [NSError errorWithDomain:@"Ubira" code:kNoUserExist userInfo:userInfo];
                    [userInfo release];
                }      
            }
        }
    }
    @catch (NSException *exception) 
    {
        TRC_EXCEPTION(exception);
        NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:kInvalidData,kError, nil];
        error = [NSError errorWithDomain:@"Ubira" code:kInvalidDataErr userInfo:userInfo];
        [userInfo release]; 
        
    }
    if (error) { 
        //Update the caller For error otherwise handleReceivedData will update the caller
        //for parsing success.
        if([self.delegate respondsToSelector:@selector(parseComplete:)])
        {
            [self.delegate parseComplete:error];
        }
    }
    return retValue;
}

/*!
 @function      handleError
 @abstract      delegate implementation
 @discussion    RemoteDataDelegate method to handle the server or parsing error and pass it
                on to the actula caller object.
 @param         NSError - Error for parsing or from the server side will be handle by this
                delegate method.
 @result        void
 */
- (void)handleError:(NSError*)error
{
	if([self.delegate respondsToSelector:@selector(parseComplete:)])
    {
        [self.delegate parseComplete:error];
    }
}

/*!
 @function      isValid
 @abstract      checks if return object is valid or not
 @discussion    checks if return object is valid or not
 @param         array - return object
                aType - type of object
 @result        BOOL - will return YES or NO 
 */
- (BOOL)isValid:(NSArray*) array for:(DataClassType) aType
{    
    switch(aType)
    {
        case kNSArray:
        {
            if ([array isKindOfClass:[NSArray class]]) {
                return TRUE;
            }
        }break;
        case kNSDictionary:
        {
            if ([array isKindOfClass:[NSDictionary class]]) {
                return TRUE;
            }
        }break;
        case kNSString:
        {
            if ([array isKindOfClass:[NSString class]]) {
                return TRUE;
            }
        }break;
    }
     
    //Data Error occured            
    NSError *error = nil;//set No network connectivity in object.		
    NSDictionary *userInfo = [[NSDictionary alloc] initWithObjectsAndKeys:kInvalidData,kError, nil];
    error = [NSError errorWithDomain:@"Ubira" code:kInvalidDataErr userInfo:userInfo];
    [userInfo release];

    //Update the caller
    if([self.delegate respondsToSelector:@selector(parseComplete:)])
    {
        [self.delegate parseComplete:error];
    }
    return FALSE;
}

/*!
 @function      isValidString
 @abstract      checks if return object is valid string
 @discussion    checks if return object is valid string
 @param         stringValue - string needs to check
 @result        BOOL - will return YES or NO 
 */
- (BOOL)isValidString:(NSString*) stringValue
{
    if ([stringValue isKindOfClass:[NSString class]]) 
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

/*!
 @function		handleReceivedData
 @abstract		response data for forgot password request to server.
 @discussion	response data for forgot password request to server.
 @param			data - response data
 @result		bool
 */
- (void)handleReceivedData:(NSData*)data
{
    
}

@end