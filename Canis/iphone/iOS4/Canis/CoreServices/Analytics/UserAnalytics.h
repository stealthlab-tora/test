//
//  UserAnalytics.h
//  Ubira
//
//  Created by [Cybage Team] on 10/06/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "CanisAppDelegate.h"
#import "CoreDataApplicationStack.h"
#import "ApplicationLifeCycle.h"
#import "Analytics.h"
#import "NSString+HTML.h"

/*!
 @class         UserAnalytics
 @abstract      This class is an operation for anyalitics for each screen.  
 @discussion    This class is an operation for anyalitics for each screen in and out.
 */
@interface UserAnalytics : NSObject 
{
    ApplicationLifeCycle    *_applicationLifeCycle;
}

@property (nonatomic, retain) ApplicationLifeCycle *applicationLifeCycle;

+ (UserAnalytics*)sharedInstance;
- (void)startApplicationLifeCycle;
- (void)stopApplicationLifeCycle;
- (void)recordScreen:(NSString*)screenName startDate:(NSDate*)startDate endDate:(NSDate*)endDate;

@end