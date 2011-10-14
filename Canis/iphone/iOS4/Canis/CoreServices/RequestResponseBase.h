//
//  RequestResponseBase.h
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "RemoteData.h"
#import "JSON.h"

@protocol RequestResponseBaseDelegate <NSObject>

- (void)parseComplete:(NSError*)error;

@end

/*!
 @class         RequestResponseBase
 @abstract      This class is base class for all the request response handler classes.  
 @discussion    This class has the Remote Data initialization which help to create an web
                service object to deals with the server.
 */
typedef enum
{
    kNSArray,
    kNSString,
    kNSDictionary
}DataClassType;

@interface RequestResponseBase : NSObject <RemoteDataDelegate> {
	id<RequestResponseBaseDelegate>     _delegate;
	RemoteData                          *webService;
}

@property (nonatomic,assign) id <RequestResponseBaseDelegate>   delegate;
@property (nonatomic,retain) RemoteData                         *webService;

- (void)createRequest;
- (BOOL)isValid:(NSArray*) array for:(DataClassType) aType;
- (BOOL)isValidString:(NSString*) stringValue;
- (BOOL)checkForErrors:(NSData*)data;

@end
