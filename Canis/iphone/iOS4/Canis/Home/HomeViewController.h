//
//  HomeViewController.h
//  Canis
//
//  Created by Yifeng on 10/4/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "SellViewController.h"

@interface HomeViewController : UIViewController
{
    // IBOutlets
    IBOutlet UIButton *sellButton;
    IBOutlet UIButton *buyButton;
    
    // ViewControllers
    SellViewController				*sellViewController;
}

@property (nonatomic, retain) UIButton *sellButton;
@property (nonatomic, retain) UIButton *buyButton;

-(IBAction)sellAction:(id)sender;
-(IBAction)buyAction:(id)sender;

@end
