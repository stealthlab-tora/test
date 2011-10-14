//
//  User.m
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "User.h"

@implementation User

@synthesize userId, email, password, autoLogin;

- (void)dealloc
{
	[userId release];
	[email release];
	[password release];
	[super dealloc];
}

@end
