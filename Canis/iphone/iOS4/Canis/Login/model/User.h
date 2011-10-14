//
//  User.h
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface User : NSObject {
	
	NSString    *userId;
	NSString    *email;
	NSString    *password;
	bool		autoLogin;
}

@property (nonatomic, copy) NSString    *userId;
@property (nonatomic, copy) NSString    *email;
@property (nonatomic, copy) NSString    *password;
@property (nonatomic, assign) bool		autoLogin;

@end
