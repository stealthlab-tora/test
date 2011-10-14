//
//  LoginViewController.h
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "RegisterViewController.h"
#import "HomeViewController.h"

/*!
 @protocol      LoginViewControllerDelegate
 @abstract      delegate for login status
 @discussion    implement delegate for login status
 */
@protocol LoginViewControllerDelegate <NSObject>
- (void)loginStatusDelegate:(BOOL)aStatus;
@end

/*!
 @class         LoginViewController
 @abstract		This class hadles releated UI interaction functionality for login.
 @discussion	This class hadles all validations for email and password along with
 implementation of TextField and ReQuestResponseBase Delegate.
 */
@interface LoginViewController : UIViewController <UITextFieldDelegate>
{
	// IBOutlets
	IBOutlet UILabel					*rememberPasswordLbl;
	IBOutlet UILabel					*newUserLbl;
	IBOutlet UITextField				*emailTxtField;
    IBOutlet UITextField				*passwordTxtField;
	IBOutlet UIButton					*loginBtn;
	IBOutlet UIButton					*forgotPasswordBtn;
	IBOutlet UIButton					*registerHereBtn;
    IBOutlet UIButton                   *checkRememberPasswordBtn;
	IBOutlet UIView						*activityIndicatorView; 
	IBOutlet UIActivityIndicatorView	*spinner;
	
	// ViewControllers
    RegisterViewController				*registerViewController;
    HomeViewController                  *homeViewController;
	
    // BOOL
	BOOL								rememberPassword;
    
    // Delegates 
	id <LoginViewControllerDelegate>	_delegate;

	// Others
    UserExtended						*_userExtended;
    CGRect                              viewFrame;
}

@property (nonatomic,assign) id <LoginViewControllerDelegate>   delegate;
@property (nonatomic, retain) IBOutlet UILabel					*rememberPasswordLbl;
@property (nonatomic, retain) IBOutlet UILabel					*newUserLbl;
@property (nonatomic, retain) IBOutlet UITextField				*emailTxtField;
@property (nonatomic, retain) IBOutlet UITextField				*passwordTxtField;
@property (nonatomic, retain) IBOutlet UIButton					*loginBtn;
@property (nonatomic, retain) IBOutlet UIButton					*forgotPasswordBtn;
@property (nonatomic, retain) IBOutlet UIButton					*checkRememberPasswordBtn;
@property (nonatomic, retain) IBOutlet UIButton					*registerHereBtn;
@property (nonatomic, retain) IBOutlet UIView					*activityIndicatorView; 
@property (nonatomic, retain) IBOutlet UIActivityIndicatorView	*spinner;

- (IBAction)backgroundTouched:(id)sender;
- (IBAction)loginAction:(id)sender;
- (IBAction)registerNewUserAction:(id)sender;
- (IBAction)forgotPasswordAction:(id)sender;
- (IBAction)rememberPasswordAction:(id)sender;

- (void)setLocalizableText;
- (void)animateView:(NSNotification*)aNotification keyboardWillShow:(BOOL)keyboardWillShow;
- (void)showAlertView:(NSString *)alertTitle alertMessage:(NSString *)alertMessage setDelegate:(id)currentDelegate;
- (void)stopActivityIndicator;

- (BOOL)validateEmailAndPassword;

@end
