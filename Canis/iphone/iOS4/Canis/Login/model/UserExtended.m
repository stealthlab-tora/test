//
//  UserExtended.m
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "UserExtended.h"

static UserExtended* userExtendedManager = nil;

@implementation UserExtended

@synthesize name, address;

/*!
 @function      sharedManager
 @abstract      create and return a shared instance
 @discussion    create and return a shared instance of userExtended manager.
 @param         nil.
 */
+ (id)sharedUserExteded
{
	@synchronized(self)
	{
		if (userExtendedManager == nil) {
			userExtendedManager = [[self alloc]init];
		}
	}
	return userExtendedManager;
}

- (void)dealloc
{
	[name release];
	[address release];
	[super dealloc];
}

@end

