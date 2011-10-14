//
//  UserAnalytics.m
//  Ubira
//
//  Created by [Cybage Team] on 10/06/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "UserAnalytics.h"

static UserAnalytics *sharedInstance = nil;

@implementation UserAnalytics
@synthesize applicationLifeCycle = _applicationLifeCycle;

/*!
 @function	globalStack
 @abstract   This fucntion will return coredata stack object.
 @discussion This is static function used in singleton implementation.
 @param       none 
 @result     
 */
+ (UserAnalytics*)sharedInstance
{
	if (sharedInstance == nil) {
        sharedInstance = [[super allocWithZone:NULL] init];
	}
	return sharedInstance;	
}

/*!
 @function	allocWithZone
 @abstract   
 @discussion 
 @param     none 
 @result     
 */
+ (id)allocWithZone:(NSZone *)zone
{
    return [[self sharedInstance] retain];
}

/*!
 @function	allocWithZone
 @abstract   
 @discussion 
 @param     none 
 @result     
 */
- (id)copyWithZone:(NSZone *)zone
{
    return self;
}

/*!
 @function	allocWithZone
 @abstract   
 @discussion 
 @param     none 
 @result     
 */
- (id)retain
{
    return self;
}

/*!
 @function		allocWithZone
 @abstract		Will return retain count
 @discussion	Will return retain count
 @param			none 
 @result		will return integer value which is retain count of this singleton object.
 */
- (NSUInteger)retainCount
{
    return NSUIntegerMax;  //denotes an object that cannot be released
}

/*!
 @function		release
 @abstract		
 @discussion 
 @param			none 
 @result     
 */
- (void)release
{
    //do nothing
}

/*!
 @function		autorelease
 @abstract		Will return self pointer.
 @discussion 
 @param			none 
 @result     
 */
- (id)autorelease
{
    return self;
}

/*!
 @function		dealloc
 @abstract		release data member variables
 @discussion	release data member variables
 @param			none
 @result		void
 */
- (void)dealloc
{
	[super dealloc];
}

/*!
 @function		exitTime
 @abstract		exit time
 @discussion	exit time for screen
 @param			screenTag - int value for each screen
 @result		bool - returns true
 */
- (BOOL)exitTime:(int)screenTag 
{
    return TRUE;
}

/*!
 @function      startApplicationLifeCycle
 @abstract      This method will start the applicatio life cycle in database.
 @discussion    This method will start the applicatio life cycle in database.
 @param         void
 @result        void
 */
- (void)startApplicationLifeCycle
{
    //Start the new life for the anayltics tracking
    self.applicationLifeCycle = [NSEntityDescription insertNewObjectForEntityForName:@"ApplicationLifeCycle" inManagedObjectContext:[[CoreDataApplicationStack globalStack] analyticsManagedObjectContext]];
    
    [self.applicationLifeCycle setApplicationLifeID:[NSString GetUUID]];
}

/*!
 @function      stopApplicationLifeCycle
 @abstract      This method will complete the one anyalitics life cycle of anyalitics.
 @param         void
 @result        void
 */
- (void)stopApplicationLifeCycle
{
    //Save the anyalitics context
    [[CoreDataApplicationStack globalStack] commitAnyaliticsData];
}

/*!
 @function      recordScreen
 @abstract      This method will log the start, end time and screen id.
 @discussion    This method will log the start, end time and screen id in database.
 @param         screenName
                startDate
                endDate
 @result        void
 */
- (void)recordScreen:(NSString*)screenName startDate:(NSDate*)startDate endDate:(NSDate*)endDate
{
    //Add the home anyalitics -with start time
    Analytics *anyalitics = [NSEntityDescription insertNewObjectForEntityForName:@"Anyalitics" inManagedObjectContext:[[CoreDataApplicationStack globalStack] analyticsManagedObjectContext]];
    
    [anyalitics setScreenID:screenName];
    [anyalitics setStartDate:startDate];
    [anyalitics setEndDate:endDate];
    [self.applicationLifeCycle addApplicationLifeToAnyaliticsObject:anyalitics];
}

@end
