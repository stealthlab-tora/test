//
//  RemoteData.h
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>

@protocol RemoteDataDelegate <NSObject>

- (void)handleReceivedData:(NSData*)data;
- (void)handleError:(NSError*)error;

@end

/*!
 @class         RemoteData
 @abstract      This class is class for the web service request response handler.  
 @discussion    This class is class for the web service request response handler.
 */
@interface RemoteData : NSObject{
	id<RemoteDataDelegate>  _delegate;
	NSURLConnection         *_connection;
    NSMutableData           *_downloadedData;
	NSURLRequest            *_urlRequest;
    NSTimer                 *requestTimer;
}

@property (nonatomic, assign) id <RemoteDataDelegate>   delegate;
@property (nonatomic, retain, readonly) NSURLConnection *connection;
@property (nonatomic, retain, readonly) NSMutableData   *downloadedData;
@property (nonatomic, retain) NSURLRequest              *urlRequest;

- (void)initWithUrlRequest:(NSURLRequest*)requestUrl delegate:(id)remoteDelegate;
- (void)makeRequest:(NSURLRequest*)requestUrl;

@end
