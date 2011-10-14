//
//  RemoteDataOperation.h
//  Ubira
//
//  Created by [Cybage Team] on 10/05/11.
//  Copyright 2011 FreeCause All rights reserved.
//

#import <Foundation/Foundation.h>
#import "Logger.h"

/*!
 @class         RemoteDataOperation
 @abstract      This class is an operation for web service call.  
 @discussion    This class has the web service asynchronous call operation.
 */
@interface RemoteDataOperation : NSOperation {
    
    //---Remote data access---
	NSURLRequest        *_remoteUrlRequest;
	NSURLConnection		*_remoteConnection;
    NSMutableData		*_remoteData;
	NSError*			_connectionError;
	
	BOOL				executing_;
	BOOL				finished_;	
	NSUInteger			contentSize;
}

@property(nonatomic,retain) NSURLRequest                *remoteUrlRequest;
@property(nonatomic,retain, readonly) NSURLConnection	*remoteConnection;
@property(nonatomic,retain, readonly) NSMutableData     *remoteData;
@property(nonatomic,readonly) NSError                   *error;
@property(nonatomic,assign) NSUInteger                  contentSize;

- (id)initWithUrlRequest:(NSURLRequest*)requestUrl;

@end
