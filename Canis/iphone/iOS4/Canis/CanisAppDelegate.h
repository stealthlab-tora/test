//
//  CanisAppDelegate.h
//  Canis
//
//  Created by Yifeng on 9/30/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "LoginViewController.h"

@interface CanisAppDelegate : NSObject <UIApplicationDelegate, LoginViewControllerDelegate, UITabBarControllerDelegate> {
    
    IBOutlet LoginViewController        *loginViewController;
    IBOutlet UIWindow                   *_window; 
    IBOutlet UITabBarController         *_tabBarController;
    IBOutlet UIView                     *activityIndicatorView; 
    IBOutlet UIActivityIndicatorView    *spinner;
    UINavigationController              *navigationController;
}

@property (nonatomic, retain) IBOutlet UIWindow             *window;
@property (nonatomic, retain) IBOutlet UITabBarController   *tabBarController;

- (void)redirectToLoginView;
- (void)setTabBarItemText;
- (void)handleLogOut;

@end