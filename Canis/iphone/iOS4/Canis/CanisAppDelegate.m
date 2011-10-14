//
//  CanisAppDelegate.m
//  Canis
//
//  Created by Yifeng on 9/30/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "CanisAppDelegate.h"

@implementation CanisAppDelegate

@synthesize window = _window;

@synthesize tabBarController = _tabBarController;

- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions
{
    // Set the application defaults
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    NSDictionary *appDefaults = [NSDictionary dictionaryWithObject:@"YES" forKey:@"enabledSpecialOffers"];
    [defaults registerDefaults:appDefaults];
    
    [defaults synchronize];
    
	// redirect to login page.
	[self redirectToLoginView];
    [self setTabBarItemText];
    
    [self.window makeKeyAndVisible];
    
    return YES;
    
}

- (void)applicationWillResignActive:(UIApplication *)application
{
    /*
     Sent when the application is about to move from active to inactive state. This can occur for certain types of temporary interruptions (such as an incoming phone call or SMS message) or when the user quits the application and it begins the transition to the background state.
     Use this method to pause ongoing tasks, disable timers, and throttle down OpenGL ES frame rates. Games should use this method to pause the game.
     */
}

- (void)applicationDidEnterBackground:(UIApplication *)application
{
    /*
     Use this method to release shared resources, save user data, invalidate timers, and store enough application state information to restore your application to its current state in case it is terminated later. 
     If your application supports background execution, this method is called instead of applicationWillTerminate: when the user quits.
     */
}

- (void)applicationWillEnterForeground:(UIApplication *)application
{
    /*
     Called as part of the transition from the background to the inactive state; here you can undo many of the changes made on entering the background.
     */
}

- (void)applicationDidBecomeActive:(UIApplication *)application
{
    /*
     Restart any tasks that were paused (or not yet started) while the application was inactive. If the application was previously in the background, optionally refresh the user interface.
     */
}

- (void)applicationWillTerminate:(UIApplication *)application
{
    /*
     Called when the application is about to terminate.
     Save data if appropriate.
     See also applicationDidEnterBackground:.
     */
}

- (void)dealloc
{
    [_tabBarController release];
    [_window release];
    [super dealloc];
}

/*
 @function      redirectToLoginView
 @abstract      redirecting to login page
 @discussion    redirecting to login page
 @param         none
 @result        void
 */
- (void)redirectToLoginView
{
	if (_tabBarController)
	{
		[_tabBarController.view removeFromSuperview];
	}
	loginViewController = [[LoginViewController alloc] initWithNibName:@"LoginViewController" bundle:[NSBundle mainBundle]];
    [loginViewController setDelegate:self];
	
	navigationController = [[UINavigationController alloc] initWithRootViewController:loginViewController];
	[navigationController setNavigationBarHidden:YES];
	
	[self.window addSubview:navigationController.view];
}

#pragma mark - Login status delegate

/*!
 @function		loginStatusDelegate
 @abstract		delgate for login result
 @discussion	checks if login is done successfully then redirecting to Home page.
 @param			aStatus - bool value to check if login done successfully or not
 @result		void
 */
- (void)loginStatusDelegate:(BOOL)aStatus
{
//    if(aStatus)
//    {
//		if (loginViewController)
//		{
//			[loginViewController.view removeFromSuperview];
//			[loginViewController release];
//		}
//        
//		if (navigationController)
//		{
//			[navigationController.view removeFromSuperview];
//			[navigationController release];
//		}
//        
//		[self.window addSubview:_tabBarController.view];
//		[_tabBarController setSelectedIndex:0];
//        _tabBarController.delegate = self;
//    }
//	else
//	{
//        [self handleLogOut];
//		[self redirectToLoginView];
//	}
    
    [self redirectToLoginView];
    [self setTabBarItemText];
}

/*!
 @function setTabBarItemText
 @abstract set tab bar items' text
 @discussio set tab bar items' text
 @param none
 @result void
 */
- (void)setTabBarItemText
{
    int index = 0;
    for(UITabBarItem *tabBarItemObj in _tabBarController.tabBar.items)
    {
        switch (index) {
            case 0:
                [tabBarItemObj setTitle: @"Home"];
                break;
            case 1:
                [tabBarItemObj setTitle: @"Search"];
                break;
            case 2:
                [tabBarItemObj setTitle: @"Favorites"];
                break;
            case 3:
                [tabBarItemObj setTitle: @"Profile"];
                break;
            case 4:
                [tabBarItemObj setTitle: @"Coupons"];
                break;
            default:
                break;
        }
        index ++;
    }
}

/*!
 @function		handleLogOut
 @abstract		Set each viewController in tabbar to its root viewcontroller
 @discussion	when logout forn user profile set each viewController in tabbar to its 
 root viewcontroller.
 @param			aStatus - bool value to check if login done successfully or not
 @result		void
 */
- (void)handleLogOut
{     
    //Set all Tab navigation controller on root.
    for (UINavigationController *viewController in self.tabBarController.viewControllers) {
        
        [viewController popToRootViewControllerAnimated:NO];
    }
} 

@end
