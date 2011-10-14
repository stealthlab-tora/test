//
//  RegisterViewController.m
//  Canis
//
//  Created by Yifeng on 10/3/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "RegisterViewController.h"
#import "CanisAppDelegate.h"

@implementation RegisterViewController
@synthesize registerLbl,mandatoryMessageLbl,termsAndConditionsBtn;
@synthesize nameTxtField;
@synthesize emailTxtField;
@synthesize passwordTxtField;
@synthesize confirmPasswordTxtField;
@synthesize addressTxtField;
@synthesize registerBtn;
@synthesize activityIndicatorView; 
@synthesize spinner;
@synthesize newUsersRegistrationRequest;

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
	[registerLbl release];
	[mandatoryMessageLbl release];
	[termsAndConditionsBtn release];
    [nameTxtField release];
	[emailTxtField release];
	[passwordTxtField release];
	[confirmPasswordTxtField release];
	[addressTxtField release];
	[registerBtn release];
	[activityIndicatorView release];
	[spinner release];
    
    [userExtended release];
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
	
	// set the navigation title
	[self.navigationController setNavigationBarHidden:NO];
	[self setLocalizableText];
    //set Initials
    viewFrame = self.view.frame;
    animatedDis=0;
    yForKeyBoard = 264;
    userExtended = [[UserExtended alloc] init];
    
    // Register the keyboard nofification to know when it appear and disappears 
	// to animate view when keyboard overlays view's textfields.
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(keyboardWillShow:) name:UIKeyboardWillShowNotification object:nil];
}

- (void)viewDidUnload
{
    // unregister for keyboard notifications while not visible.
    [[NSNotificationCenter defaultCenter] removeObserver:self name:UIKeyboardWillShowNotification object:nil]; 
    
    [self setRegisterLbl:nil];
    [self setMandatoryMessageLbl:nil];
    [self setNameTxtField:nil];
    [self setEmailTxtField:nil];
    [self setPasswordTxtField:nil];
    [self setConfirmPasswordTxtField:nil];
    [self setAddressTxtField:nil];
    [self setTermsAndConditionsBtn:nil];     
    [self setRegisterBtn:nil];
    [self setActivityIndicatorView:nil];
    [self setSpinner:nil];
    
    [super viewDidUnload];
}


- (void)viewWillAppear:(BOOL)animated
{
    [[self.navigationController navigationBar] setTintColor:[UIColor colorWithRed:.6 green:0.0 blue:0.0 alpha:1.0]];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

#pragma mark -
#pragma mark KeyBoard delegate methods
/*!
 @function      keyboardWillShow
 @abstract      keyboard about to appear on view
 @discussion	keyboard about to appear on view
 @param         aNotification - notificationo object 
 @result		void
 */
- (void)keyboardWillShow:(NSNotification*)aNotification
{	
	// get size of keyboard
	NSDictionary *info = [aNotification userInfo];
	NSValue *aValue = [info objectForKey:UIKeyboardFrameEndUserInfoKey];
    yForKeyBoard = [aValue CGRectValue].origin.y;
}

#pragma mark 
#pragma mark TextField delegate Methods
/*!
 @function      textFieldShouldReturn
 @abstract      delegate for textField 
 @discussion    discard the keyboard when tap on return button of keyboard
 @param         textField - selected textField 
 @result        will return YES 
 */
- (BOOL)textFieldShouldReturn:(UITextField *)textField 
{
	// descard the keyboard
	[textField resignFirstResponder];
	return YES;
}

/*!
 @function      shouldChangeCharactersInRange
 @abstract      delegate for textField 
 @discussion    entered text should replace
 @param         range - on which entered text needs to replace 
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

/*!
 @function      textFieldDidBeginEditing
 @abstract      delegate for textField 
 @discussion    beign to end of text input animateView accordingly.
 @param         text - selected textfields 
 @result        void 
 */
- (void)textFieldDidBeginEditing:(UITextField *)textField
{
    [self animateTextField:textField up:YES];
}

/*!
 @function      textFieldDidEndEditing
 @abstract      delegate for textField 
 @discussion    did end of text input animateView accordingly.
 @param         text - selected textfields 
 @result        void  
 */
- (void)textFieldDidEndEditing:(UITextField *)textField
{
    [self animateTextField:textField up:NO];
}

/*!
 @function      animateTextField
 @abstract		animate view up or down when keyboard overlays
 @discussion	Common function to animate view up or down when keyboard overlays
 view's textfield
 @param			keyboardWillHide
 @result		void
 */
- (void)animateTextField: (UITextField*) textField up: (BOOL) up
{
    CGPoint temp = [textField.superview convertPoint:textField.frame.origin toView:nil];
    if(up)
    {
        int moveUpValue = temp.y+textField.frame.size.height;
        animatedDis = yForKeyBoard-(viewFrame.size.height-moveUpValue-5);
    }
    else
    {
        [self viewAnimationwithFrame:viewFrame]; 
        return;
    }
    
    if(animatedDis>0)
    {
        const int movementDistance = animatedDis;
        int movement = (up ? -movementDistance : movementDistance);
        [self viewAnimationwithFrame:CGRectOffset(self.view.frame, 0, movement)];
    }
}

/*!
 @function		viewAnimationwithFrame
 @abstract		animate view up or down 
 @discussion	Common function to animate view up or down when keyboard overlays
 view's textfield
 @param			currentFrame - Frame to set for View animation
 @result		void
 */
- (void)viewAnimationwithFrame:(CGRect)currentFrame
{
    [UIView beginAnimations: nil context: nil];
    [UIView setAnimationBeginsFromCurrentState: YES];
    [UIView setAnimationDuration: 0.3f];
    
    self.view.frame = currentFrame;       
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
	[nameTxtField resignFirstResponder];
	[emailTxtField resignFirstResponder];
	[passwordTxtField resignFirstResponder];
	[confirmPasswordTxtField resignFirstResponder];
	[addressTxtField resignFirstResponder];
}

/*!
 @method			registerAction
 @abstract		register the user
 @discussion     register the user if all validation done successful
 */
- (IBAction)registerAction:(id)sender 
{
	// Validate all text fields
	if ([self validateTextFields])
	{
		userExtended.name = nameTxtField.text;
		userExtended.email = emailTxtField.text;
		userExtended.password = passwordTxtField.text;
		userExtended.address = addressTxtField.text;

		// TODO
        // register here
        self.newUsersRegistrationRequest = kRegistrationRequest;
        [self registerComplete: nil];
		
		// Show indicator while registration activity is running
		[self.view addSubview:activityIndicatorView];
		[spinner startAnimating];
		self.navigationController.navigationBar.userInteractionEnabled = NO;
	}
}

#pragma mark 
#pragma mark AlertView Methods
/*!
 @function		showAlertView
 @abstract		Common method to display alert message 
 @discussion	Common method to display alert message 
 @param			alertTitle - Title for AlertView
 alertMessage - Message description for AlertView		
 @result		void
 */
- (void)showAlertView:(NSString *)alertTitle alertMessage:(NSString *)alertMessage setDelegate:(id)currentDelegate
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
    UserExtended *sharedObj = [UserExtended sharedUserExteded];
    sharedObj.email = userExtended.email;
    sharedObj.password = userExtended.password;
    
    // Show indicator while registration activity is running
    [self.view addSubview:activityIndicatorView];
    [spinner startAnimating];
    self.navigationController.navigationBar.userInteractionEnabled = NO;
    
    self.newUsersRegistrationRequest = kLoginRequest;
    
    [self registerComplete: nil];
}

#pragma mark - Register complete delegate
/*!
 @function      registerComplete
 @abstract      delegat on register complete.
 @discussion    Take the action based on the parameter.
 @param         error - server response if no error it will be nil.
 */
- (void)registerComplete:(NSError*)error
{
    // Remove activity indicator view.
    [self stopActivityIndicator];
    
    if(error)
    {
        // TODO error handling
    }
    else
    {
        //update UI
        switch (self.newUsersRegistrationRequest)
        {
            case kRegistrationRequest:
			{
				// Show successful message.
                [self showAlertView: @"Registration Successful" alertMessage: @"Your are now successfully registered with Canis" setDelegate:self];
			}	
                break;
            case kLoginRequest:
			{
				// send response to ubiraAppdelegate about login
                CanisAppDelegate *canis = (CanisAppDelegate *)[[UIApplication sharedApplication]delegate];
                [canis loginStatusDelegate:YES];
			}	
                break;
                
            default:
                break;
        }
	}
}

#pragma mark 
#pragma mark  Other Methods

-(void) setLocalizableText
{
    return;
}

/*!
 @function      validateTextFields
 @abstract      validate all text fields
 @discussion    validate all text fields
 @param         none
 @result        will return YES is all textField comply the business rule else NO
 */
- (BOOL)validateTextFields
{
    // TODO validation
	return YES;
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
	// stop running activity indicator
	[spinner stopAnimating];
	[activityIndicatorView removeFromSuperview];
	self.navigationController.navigationBar.userInteractionEnabled = YES; 
}

@end