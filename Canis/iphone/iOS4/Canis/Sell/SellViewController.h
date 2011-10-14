//
//  SellHomeViewController.h
//  Canis
//
//  Created by Yifeng on 10/5/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface SellViewController : UIViewController <UINavigationControllerDelegate, UIImagePickerControllerDelegate>
{
    // IBOutlets
    IBOutlet UIButton *takePhotoButton;
    IBOutlet UIButton *saveButton;
    IBOutlet UIImageView *bigImageView;
    IBOutlet UIImageView *thumbnailImageView;
    IBOutlet UITextField *nameTxtField;
    IBOutlet UITextField *descriptionTxtField;
}

@property (nonatomic, retain) UIButton *takePhotoButton, *saveButton;
@property (nonatomic, retain) UIImageView *bigImageView, *thumbnailImageView;
@property (nonatomic, retain) UITextField *nameTxtField, *descriptionTxtField;

-(IBAction)takePhotoAction:(id)sender;
-(IBAction) saveAction:(id)sender;

-(void)capturePhoto;
- (void)showAlertView:(NSString *)alertTitle alertMessage:(NSString *)alertMessage setDelegate:(id)currentDelegate;

@end
