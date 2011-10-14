//
//  RegisterViewController.h
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UserExtended.h"

typedef enum
{
	kRegistrationNone,
	kRegistrationRequest,
	kLoginRequest,
}NewUserRegistrationState;

/*!
 @class			ForgotPasswordViewController
 @abstract		This class hadles releated UI interaction functionality for forgotPassword.
 @discussion	This class hadles all validations for email and password along with
 implementation of TextField and ReQuestResponseBase Delegate.It also 
 contains its own delegate method RegisterViewControllerDelegate.
 */

@interface RegisterViewController : UIViewController <UITextFieldDelegate>
{
	// IBOutlets
	IBOutlet UILabel					*registerLbl;
	IBOutlet UILabel					*mandatoryMessageLbl;
	IBOutlet UIButton					*termsAndConditionsBtn;
	IBOutlet UITextField				*nameTxtField;
	IBOutlet UITextField				*emailTxtField;
	IBOutlet UITextField				*passwordTxtField;
	IBOutlet UITextField				*confirmPasswordTxtField;
	IBOutlet UITextField				*addressTxtField;
//	IBOutlet UIButton					*termAndConditionsChkBtn;
	IBOutlet UIButton					*registerBtn;
	IBOutlet UIView						*activityIndicatorView; 
	IBOutlet UIActivityIndicatorView	*spinner;
    
	// BOOL
	BOOL								isViewAnimated;
	
	// Others
	UserExtended                        *userExtended;
    NewUserRegistrationState            newUsersRegistrationRequest;
    CGRect                              viewFrame;    
    NSInteger                           animatedDis;
    CGFloat                             yForKeyBoard;
}

@property (nonatomic, retain) IBOutlet UILabel					*registerLbl;
@property (nonatomic, retain) IBOutlet UILabel					*mandatoryMessageLbl;
@property (nonatomic, retain) IBOutlet UIButton					*termsAndConditionsBtn;
@property (nonatomic, retain) IBOutlet UITextField				*nameTxtField;
@property (nonatomic, retain) IBOutlet UITextField				*emailTxtField;
@property (nonatomic, retain) IBOutlet UITextField				*passwordTxtField;
@property (nonatomic, retain) IBOutlet UITextField				*confirmPasswordTxtField;
@property (nonatomic, retain) IBOutlet UITextField				*addressTxtField;
@property (nonatomic, retain) IBOutlet UIButton					*registerBtn;
@property (nonatomic, retain) IBOutlet UIView					*activityIndicatorView; 
@property (nonatomic, retain) IBOutlet UIActivityIndicatorView	*spinner;
@property (nonatomic)  NewUserRegistrationState                 newUsersRegistrationRequest;

- (void)setLocalizableText;

- (void)showAlertView:(NSString *)alertTitle alertMessage:(NSString *)alertMessage setDelegate:(id)currentDelegate;

- (void)animateTextField: (UITextField*) textField up:(BOOL)up;
- (void)viewAnimationwithFrame:(CGRect)currentFrame;

- (void)stopActivityIndicator;
- (BOOL)validateTextFields;

- (void) registerComplete: (NSError *) error;

- (IBAction)backgroundTouched:(id)sender;
- (IBAction)registerAction:(id)sender; 

@end
