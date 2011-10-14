//
//  UserExtended.h
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "User.h"

@interface UserExtended : User {
    
	NSString    *name;
	NSString    *address;
	
}

@property (nonatomic, copy) NSString    *name;
@property (nonatomic, copy) NSString    *address;

+ (id)sharedUserExteded;
@end

