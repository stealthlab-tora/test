//
//  SellHomeViewController.m
//  Canis
//
//  Created by Yifeng on 10/5/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import "SellViewController.h"
#import "Commons.h"
#import "UIImage+Resize.h"
#import "ASIFormDataRequest.h"

@implementation SellViewController

@synthesize takePhotoButton, saveButton, bigImageView, thumbnailImageView, nameTxtField, descriptionTxtField;

- (void) dealloc
{
    [takePhotoButton release];
    [saveButton release];
    [bigImageView release];
    [thumbnailImageView release];
    [nameTxtField release];
    [descriptionTxtField release];
    [super dealloc];
}

- (IBAction) takePhotoAction:(id)sender
{
    [self capturePhoto];

}

- (IBAction) saveAction:(id)sender
{
    // call API to store images on Azure and URL in DB
    // Upload thumbnail and big image
    NSString *uploadURL = [NSString stringWithFormat:@"%@/%@", kCanisServerUrl, @"ItemSaveController.php"];
    NSURL *serverURL = [NSURL URLWithString: uploadURL];
    ASIFormDataRequest *request = [ASIFormDataRequest requestWithURL:serverURL];
    NSData *thumbnailData = UIImageJPEGRepresentation(thumbnailImageView.image, 1.0f);
    [request setData:thumbnailData withFileName:@"canis-image-thumbnail.jpg" andContentType:@"image/jpeg" forKey:@"imageThumbnail"];
    
    NSData *bigImageData = UIImageJPEGRepresentation(bigImageView.image, 1.0f);
    [request setData:bigImageData withFileName:@"canis-image.jpg" andContentType:@"image/jpeg" forKey:@"image"];
    
    [request startSynchronous];
    NSError *error = [request error];
    if (!error) {
        NSString *response = [request responseString];
        TRC_DBG(@"Response: %@", response);
        if ([response isEqualToString:@"true"]) {
            // transaction successful
            [self showAlertView:@"Photo Saved." alertMessage:@"Selected photo has been saved in Azure." setDelegate:nil];
            
        } else {
            // failed
            [self showAlertView:@"Server Error." alertMessage:@"Internal server error." setDelegate:nil];
        }
        
    } else {
        TRC_ERR(@"Error when uploading photo to API server %@", error);
    }
}

- (void) capturePhoto
{
    // TODO take photo using camera
    UIImagePickerControllerSourceType sourceType = UIImagePickerControllerSourceTypePhotoLibrary;
//    UIImagePickerControllerSourceType sourceType = UIImagePickerControllerSourceTypeCamera;
    if ( [UIImagePickerController isSourceTypeAvailable: sourceType] ) {
        UIImagePickerController *imagePicker = [[[UIImagePickerController alloc] init] autorelease];
        imagePicker.delegate = self;
        imagePicker.sourceType = sourceType;
//        imagePicker.allowsEditing = YES;
        [self presentModalViewController: imagePicker animated: YES];
    }
}

- (UIImage *) resizeImage:(UIImage *)origin to:(CGSize)newSize
{
    UIGraphicsBeginImageContext(newSize);
    [origin drawInRect:CGRectMake(0, 0, newSize.width, newSize.height)];
    UIImage* newImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
      
    return newImage;
}

// invoked after photo selected
- (void) imagePickerController:(UIImagePickerController *)picker didFinishPickingMediaWithInfo:(NSDictionary *)info
{
    
    [self.view addSubview: bigImageView];
    [self.view addSubview: thumbnailImageView];
   
    UIImage *originalImage = [info objectForKey: UIImagePickerControllerOriginalImage];

    if (originalImage) {
        
        // resize to thumbnail size
        UIImage *thumbnail = [originalImage thumbnailImage:kThumbnailSize transparentBorder:0 cornerRadius:0 interpolationQuality:kCGInterpolationDefault];
        thumbnailImageView.image = thumbnail;
       
        // resize to big image size
        CGSize bigImageSize = CGSizeMake(kBigImageWidth, kBigImageHeight);
        UIImage *bigImage = [originalImage resizedImage:bigImageSize interpolationQuality:kCGInterpolationDefault];
        bigImageView.image = bigImage;

        originalImage = nil;
    }
    
    // close photo album
    [self dismissModalViewControllerAnimated: YES];
}

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
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
@end
