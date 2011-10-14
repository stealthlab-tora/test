//
//  LoginViewController.m
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "LoginViewController.h"
#import "UserExtended.h"
#import "CanisAppDelegate.h"
#import "ASIFormDataRequest.h"

@implementation LoginViewController

@synthesize delegate = _delegate;
@synthesize emailTxtField;
@synthesize passwordTxtField;
@synthesize checkRememberPasswordBtn;
@synthesize activityIndicatorView; 
@synthesize spinner;
@synthesize rememberPasswordLbl, newUserLbl, registerHereBtn, forgotPasswordBtn;
@synthesize loginBtn;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)dealloc
{
    _delegate= nil;
	[rememberPasswordLbl release];
	[newUserLbl release];
	[registerHereBtn release];
    [emailTxtField release];
    [passwordTxtField release];
    [activityIndicatorView release];
	[loginBtn release];
    [spinner release];
    [super dealloc];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}

#pragma mark - View lifecycle

- (void)viewDidLoad
{
    [super viewDidLoad];
	
//    [[self.navigationController navigationBar] setTintColor: [UIColor colorWithRed:.6 green:0.0 blue:0.0 alpha:1.0]];
//    [self.view setBackgroundColor: [UIColor colorWithRed:.6 green:0.0 blue:0.0 alpha:1.0]];
	[self setLocalizableText];
	
    //set Initials
    viewFrame = self.view.frame;
    
	// set rememberPassword default to NO.
	rememberPassword = NO;
	_userExtended = [UserExtended sharedUserExteded];

}

- (void)viewWillAppear:(BOOL)animated
{
    [self.navigationController setNavigationBarHidden:YES];
	
	// Register the keyboard nofification to know when it appear and disappears 
	// to animate view when keyboard overlays view's textfields. 
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(keyboardWillShow:) name:UIKeyboardWillShowNotification object:nil];
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(keyboardWillHide:) name:UIKeyboardWillHideNotification object:nil];
}

- (void)viewDidUnload
{
    [self setRememberPasswordLbl:nil];
    [self setNewUserLbl:nil];
    [self setEmailTxtField:nil];
    [self setPasswordTxtField:nil];
    [self setLoginBtn:nil];
    [self setRegisterHereBtn:nil];
    [self setActivityIndicatorView:nil];
    [self setSpinner:nil];
    
    [super viewDidUnload];
}

- (void)viewWillDisappear:(BOOL)animated
{
	// unregister for keyboard notifications while not visible.
    [[NSNotificationCenter defaultCenter] removeObserver:self name:UIKeyboardWillShowNotification object:nil]; 
	[[NSNotificationCenter defaultCenter] removeObserver:self name:UIKeyboardWillHideNotification object:nil]; 	
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

/*!
 @function      textFieldShouldReturn
 @abstract      delegate for textField 
 @discussion    discard the keyboard when tap on return button of keyboard
 @param         textField - selected textField 
 @result        will return YES 
 */
- (BOOL)textFieldShouldReturn:(UITextField *)textField 
{
	// Discard keyBoard form window when return key tap on keyboard.
	[textField resignFirstResponder];
	return YES;
}

#pragma TextField Delegate methods
/*!
 @function		shouldChangeCharactersInRange
 @abstract		delegate for textField 
 @discussion    entered text should replace
 @param			range - on which entered text needs to replace 
 string - needs to replace
 @result        BOOL - if space entered returns NO else YES. 
 */
- (BOOL)textField:(UITextField *)textField shouldChangeCharactersInRange:(NSRange)range replacementString:(NSString *)string
{
    if ((range.location > 0 && [string length] > 0 && [[NSCharacterSet whitespaceCharacterSet] characterIsMember:[string characterAtIndex:0]] &&           [[NSCharacterSet whitespaceCharacterSet] characterIsMember:[[textField text] characterAtIndex:range.location - 1]])) 
    {
        return NO;
    }
    // if first character is a space
    if ([string isEqualToString:@" "] && [textField.text length]==0)
	{ 
		return NO;
	}
    return YES;
}

#pragma mark -
#pragma mark KeyBoard delegate methods
/*!
 @function		keyboardWillShow
 @abstract		keyboard about to appear on view
 @discussion	keyboard about to appear on view
 @param			nNotification - notificationo object 
 @result		void
 */
- (void)keyboardWillShow:(NSNotification*)aNotification
{	
	[self animateView:aNotification keyboardWillShow:YES];
}

/*!
 @function		keyboardWillHide
 @abstract		keyboard about to disappear from view
 @discussion		keyboard about to disappear from view
 @param			aNotification - Notification object
 @result			void
 */
- (void)keyboardWillHide:(NSNotification*)aNotification
{
	[self animateView:aNotification keyboardWillShow:NO];
}

/*!
 @function      animateView
 @abstract		animate view up or down when keyboard overlays
 @discussion	Common function to animate view up or down when keyboard overlays
 view's textfield
 @param			keyboardWillHide
 @result		void
 */
- (void)animateView:(NSNotification*)aNotification keyboardWillShow:(BOOL)keyboardWillShow
{
	[UIView beginAnimations:nil context:NULL];
	
	[UIView setAnimationDuration:0.3];
	
	CGRect rect = [[self view] frame];
	if (keyboardWillShow)
	{
		rect.origin.y -= 60;
	}
	else
	{
        rect = viewFrame;
	}
	[[self view] setFrame: rect];
	
	[UIView commitAnimations];
}

#pragma mark 
#pragma mark Action Methods
/*!
 @method		backgroundTouched
 @abstract		discard keyboard when tapped on view apart from keyBoard
 @discussion	discard keyboard when tapped on view apart from keyBoard
 */
- (IBAction)backgroundTouched:(id)sender
{
	[emailTxtField resignFirstResponder];
	[passwordTxtField resignFirstResponder];
}

/*!
 @method		loginAction
 @abstract		login for user
 @discussion	initiate login process for user
 */
- (IBAction)loginAction:(id)sender 
{
	// Discard the keyboard from window
	[emailTxtField resignFirstResponder];
	[passwordTxtField resignFirstResponder];
	
	// If entered email and password are valide initiate login process
	if ([self validateEmailAndPassword])
	{	
        // show the activityIndicator while login activity is running. 
		[self.view addSubview:activityIndicatorView];
		[spinner startAnimating];
        
		// set email and password in singleton object to use through out the application.
		_userExtended.email = emailTxtField.text;
		_userExtended.password = passwordTxtField.text;
		
        // Show successful message.
//        [self showAlertView: @"Login Successful" alertMessage: @"Your are now successfully logined." setDelegate:self];
        NSString *loginURL = [NSString stringWithFormat:@"%@/%@", kCanisServerUrl, @"LoginController.php"];
        
        NSURL *serverURL = [NSURL URLWithString: loginURL];
        ASIFormDataRequest *request = [ASIFormDataRequest requestWithURL:serverURL];
        [request setPostValue:_userExtended.email forKey:@"email"];
        [request setPostValue:_userExtended.password forKey:@"password"];
              
        [request startSynchronous];
        NSError *error = [request error];
        if (!error) {
            NSString *response = [request responseString];
            TRC_DBG(@"Response: %@", response);
            if ([response isEqualToString:@"true"]) {
                // TODO we just show the HOME page here
                homeViewController = [[HomeViewController alloc] initWithNibName:@"HomeViewController" bundle:[NSBundle mainBundle]];
                [self.navigationController pushViewController:homeViewController animated:YES];
                
                [homeViewController release];

            } else {
                // email or password is wrong
                [self showAlertView:@"Please Login" alertMessage:@"The email or passworld you entered is incorrect." setDelegate:nil];
            }
        } else {
            TRC_ERR(@"Can not login using API server %@", error);
        }

	}
}


/*!
 @method		registerNewUserAction
 @abstract		redirect to registration page
 @discussion	redirect to registration page when tapping new user registration button 
 */
- (IBAction)registerNewUserAction:(id)sender 
{
    registerViewController = [[RegisterViewController alloc] initWithNibName:@"RegisterViewController" bundle:[NSBundle mainBundle]];
    [self.navigationController pushViewController:registerViewController animated:YES];
    
    [registerViewController release];
}

- (IBAction)forgotPasswordAction:(id)sender
{
    // TODO
}

/*! 
 @method		rememberPasswordAction
 @abstract		check the rememberPassword button
 @discussion	set the check or uncheck image for button and set the rememberpassword
 bool variable
 */
- (IBAction)rememberPasswordAction:(id)sender
{
	rememberPassword = !rememberPassword;
	UIImage *checkImg =	rememberPassword ? [UIImage imageNamed:@"mark.png"] : [UIImage imageNamed:@"unMark.png"];
	[checkRememberPasswordBtn setBackgroundImage:checkImg forState:UIControlStateNormal];
}

#pragma mark 
#pragma mark Other Methods
/*!
 @function      setLocalizableText
 @abstract      set localizable text 
 @discussion    set localizable text for UI
 @param         none
 @result        void
 */
- (void)setLocalizableText
{
	// set text for navigation title, buttons and textfield's placeholder text
	[self setTitle:kLogin];
    
	[self.emailTxtField setPlaceholder:kEmailAddress];
	[self.passwordTxtField setPlaceholder:kPassword];
//	[self.rememberPasswordLbl setText:kRememberPasswordLblText];
//	[self.newUserLbl setText:kNewUser];
//	
//	[self.loginBtn setTitle:kLogin forState:UIControlStateNormal];
//	[self.forgotPasswordBtn setTitle:kForgotPassword forState:UIControlStateNormal];
//	[self.registerHereBtn setTitle:kRegisterHere forState:UIControlStateNormal];
}

/*!
 @function		validateEmailAndPassword
 @abstract		validate email and password 
 @discussion	validate email and password 
 @param			none
 @result		will return YES if email and password both will comply  
 business rule else NO
 */
- (BOOL)validateEmailAndPassword
{	
	return YES;
}

/*!
 @function		showAlertView
 @abstract		Common method to display alert message 
 @discussion	Common method to display alert message 
 @param			alertTitle - Title for AlertView
 alertMessage - Message description for AlertView		
 @result			void
 */
- (void)showAlertView:(NSString *)alertTitle alertMessage:(NSString *)alertMessage setDelegate:(id)currentDelegate;
{
	UIAlertView *alert = [[UIAlertView alloc] initWithTitle:alertTitle message:alertMessage delegate:currentDelegate cancelButtonTitle: @"OK" otherButtonTitles:nil, nil];
	[alert show];
	[alert release];
}

/*!
 @method		clickedButtonAtIndex
 @abstract		redirect to Login Page 
 @discussion	redirect to Login Page when password sent successfully
 */
- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{   
    // Show indicator while registration activity is running
    [self.view addSubview:activityIndicatorView];
    [spinner startAnimating];
    self.navigationController.navigationBar.userInteractionEnabled = NO;
    
    // TODO we just show the HOME page here
    homeViewController = [[HomeViewController alloc] initWithNibName:@"HomeViewController" bundle:[NSBundle mainBundle]];
    [self.navigationController pushViewController:homeViewController animated:YES];
    
    [homeViewController release];
}


/*!
 @function      stopActivityIndicator
 @abstract		stop activity indicator
 @discussion	stop activity indicator and remove from superview
 @param			none
 @result		Void
 */
- (void)stopActivityIndicator
{
	[spinner stopAnimating];
	[activityIndicatorView removeFromSuperview];
}

@end