//
//  HomeViewController.m
//  Canis
//
//  Created by Yifeng on 10/4/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "HomeViewController.h"

@implementation HomeViewController

@synthesize sellButton, buyButton;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void) dealloc
{
    [sellButton release];
    [buyButton release];
    [super dealloc];
}

/*!
 @method		sellAction
 @abstract		user to sell items
 @discussion	user to sell items
 */
- (IBAction) sellAction:(id)sender
{
    sellViewController = [[SellViewController alloc] initWithNibName:@"SellViewController" bundle:[NSBundle mainBundle]];
    [self.navigationController pushViewController:sellViewController animated:YES];
    
    [sellViewController release];

}

/*!
 @method		sellAction
 @abstract		user to sell items
 @discussion	user to sell items
 */
- (IBAction) buyAction:(id)sender
{

}

- (void)didReceiveMemoryWarning
{
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

#pragma mark - View lifecycle	

- (void)viewDidLoad
{
    [super viewDidLoad];
	
	// set the navigation title
	[self.navigationController setNavigationBarHidden:NO];
    self.navigationController.navigationBar.userInteractionEnabled = YES; 
    
    // Register the keyboard nofification to know when it appear and disappears 
	// to animate view when keyboard overlays view's textfields.
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(keyboardWillShow:) name:UIKeyboardWillShowNotification object:nil];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
    // Return YES for supported orientations
    return (interfaceOrientation == UIInterfaceOrientationPortrait);
}

@end
