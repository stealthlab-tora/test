//
//  Commons.h
//  Canis
//
//  Created by Yifeng on 10/5/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

// image size
#define kThumbnailSize      64
#define kBigImageWidth      320.0f
#define kBigImageHeight     320.0f
#define kVeryBigImageWidth  640.0f
#define kVeryBigImageHeight 640.0f

// Titles

#define kHomeTitle			NSLocalizedString(@"Home", @"")
#define kStoreTitle			NSLocalizedString(@"Store List", @"")
#define kAddStore			NSLocalizedString(@"Add Store",@"")
#define kSearchTitle		NSLocalizedString(@"Search",@"")
#define kFavouritesTitle	NSLocalizedString(@"Favorites",@"")
#define kProfileTitle		NSLocalizedString(@"Profile",@"")
#define kProfileTabTitle	NSLocalizedString(@"ProfileTab_Title",@"")
#define kCouponsTitle		NSLocalizedString(@"Coupons",@"")
#define kReviewTitle		NSLocalizedString (@"Reviews", "")

#define kBack               NSLocalizedString(@"Back",@"")
#define kDone				NSLocalizedString(@"Done",@"")
#define kWelcome			NSLocalizedString(@"Welcome",@"")

// NO Network connection
#define kNoNetwork          NSLocalizedString(@"Network not available",@"")
#define kServerError		NSLocalizedString(@"Server_Error",@"")

#define kInvalidData        NSLocalizedString(@"Invalid_Data_From_Server",@"")

/** Login **/
#define kUbiraTitle			NSLocalizedString(@"UBIRA",@"")
#define kLoginTitle			NSLocalizedString(@"Login",@"")

// Login Module  
#define kLogin NSLocalizedString(@"Login", @"")
#define kPassword NSLocalizedString(@"Password", @"")
#define kEmailAddress NSLocalizedString(@"EmailAddress", @"")
#define kRememberPasswordLblText NSLocalizedString(@"Remember_Password", @"")
#define kNewUser NSLocalizedString(@"New_User", @"")
#define kRegisterHere NSLocalizedString(@"Register_Here", @"")

#define kEmailAndPasswordFromKeychain NSLocalizedString(@"Ubira", @"")

#define kPleaseLogin NSLocalizedString(@"Please_Login", @"")
#define kPleaseLoginDescription NSLocalizedString(@"Login_Description",@"")

#define kButtonOk NSLocalizedString(@"OK", @"")
#define kButtoncancel NSLocalizedString(@"Cancel", @"")

#define kEmailInvalid NSLocalizedString(@"Email_Invalid", @"")
#define kEmailInvalidDescription NSLocalizedString(@"Email_Invalid_Description", @"")

#define kNoEmailIdDescription NSLocalizedString(@"NoEmailIdDescription", @"")

#define kPasswordInvalid NSLocalizedString(@"Password_Invalid", @"")
#define kPasswordInvalidDescription NSLocalizedString(@"PasswordInvalidDescription", @"")
#define kPasswordLengthInvalidDescription NSLocalizedString(@"Password_Length_Invalid_Description", @"")
#define kPasswordBusinessRuleInvalidDescription NSLocalizedString(@"Password_BusinessRule_Invalid_Description", @"")
#define kConfirmPasswordInvalidDescription NSLocalizedString(@"ConfirmPassword_Invalid_Description", @"")

#define kPasswordSent NSLocalizedString(@"Password_Sent", @"")
#define kPasswordSentDescription NSLocalizedString(@"PasswordSent_Description", @"")

// Forgot Password
#define kForgotPassword NSLocalizedString(@"Forgot_Password", @"")
#define kForgot_Password_WithoutQuestionMark NSLocalizedString(@"Forgot_Password_WithoutQuestionMark", @"")

#define kForgotEmailText NSLocalizedString(@"Enter_Email_Address", @"")
#define kNewUserRegistration NSLocalizedString(@"New_User_Registration", @"")

// Registration
#define kRegister NSLocalizedString(@"Register", @"")
#define kAllFieldsAreMandatory NSLocalizedString(@"All_Fields_Are_Mandatory", @"")
#define kAcceptTermsAndConditions NSLocalizedString(@"Accept_Terms_&_Conditions", @"")

#define kRegistration NSLocalizedString(@"Registration_Successful", @"")
#define kRegistrationDescription NSLocalizedString(@"Registration_Description", @"")
#define kNoRegistrationDescription NSLocalizedString(@"No_Registration_Description", @"")
#define kTermsAndConditionDescription NSLocalizedString(@"Terms_And_Condition_Description", @"") 

#define kRememberPassword NSLocalizedString(@"Remember_Password", @"")

#define kNameInvalid NSLocalizedString(@"Name_Invalid", @"")
#define kNameInvalidDescription NSLocalizedString(@"NameInvalidDescription", @"")

// Home
#define kIndexOfSelectedOffer NSLocalizedString(@"IndexofOfferSelected", @"")
#define kOffersForYou NSLocalizedString(@"Offers_For_You", @"")
#define kCrunchBusterOffer NSLocalizedString(@"Crunch_Buster_Offer", @"")
#define kCheckInButtonTitle NSLocalizedString(@"Check_In", @"")
#define kCheckOutButtonTitle NSLocalizedString(@"Check_Out", @"")
#define kNoticesHeaderText NSLocalizedString(@"Notices", @"")

// Store
#define kStoreLocation NSLocalizedString(@"Store_Location", @"")
#define kStoreLocationDescription NSLocalizedString(@"Store_Location_Description", @"")

#define kNoStoreFound NSLocalizedString(@"No_Stores_Found", @"")
#define kNoStoreFoundDescription NSLocalizedString(@"No_Stores_Found_Description", @"")
 
#define kButtonCreateStore NSLocalizedString(@"Create_Store", @"")

// Add Store 
#define kStoreMandatoryText NSLocalizedString(@"Store_Name_Is_Mandatory", @"")
#define kStoreNamePlaceHolderText NSLocalizedString(@"Store_Name", @"")
#define kStoreAddressPlaceHolderText NSLocalizedString(@"Address", @"")
#define kStoreTelNoPlaceHolderText NSLocalizedString(@"Tel_No", @"")
#define kStoreDescriptionPlaceHolderText NSLocalizedString(@"Store_Desceription", @"")
#define kStoreSubmitButtonText NSLocalizedString(@"Submit", @"")

#define kStoreAddedSuccessfully NSLocalizedString(@"Store_Added_successfully", @"")
#define kStoreAddedSuccessDescription NSLocalizedString(@"Store_Added_successfully_Description", @"")

#define kNoStoreAddedSuccessfullyDescription NSLocalizedString(@"No_Store_Added_successfully_Description", @"")
#define kPhoneInvalid NSLocalizedString(@"Phone_Invalid", @"")
#define kPhoneInvalidDescription NSLocalizedString(@"Phone_Invalid_Description", @"")

#define kCheckInDescription  NSLocalizedString(@"Location Service Disabled", @"")

//  Search
#define kSearchBarPlaceHolder NSLocalizedString(@"Search_Bar_Placeholder", @"")
#define kPleaseCheckInText  NSLocalizedString(@"Please_CheckIn_Text", @"")

//Products
#define kProductDetails NSLocalizedString(@"Product Details", @"")

#define kProductReviews NSLocalizedString(@"Reviews", @"")

#define kCancelButton NSLocalizedString(@"Cancel", @"")

#define kNoProductFound NSLocalizedString (@"No_Products_found","")

#define kNoProductFoundDesc NSLocalizedString (@"No_Products_Barcode", "")

#define kNoResultFound NSLocalizedString (@"No_Results_found","")

#define kNoResultFoundDesc NSLocalizedString (@"No_results_found", "")

#define kUpdateButtonTitle NSLocalizedString(@"Update", @"")

#define kBuyButtonTitle NSLocalizedString(@"Buy", @"")

#define kFacebookUpdateSuccessful NSLocalizedString(@"Facebook_update_successful", @"")
#define kFacebookUpdateSuccessfulDesctiption NSLocalizedString(@"Facebook_update_successful_Description", @"")

#define kPleaseWaitText NSLocalizedString(@"Please_Wait", @"")
#define kLoginToTwitterText NSLocalizedString(@"Login_to_Twitter", @"")
#define kTwitterUpdateSuccessful NSLocalizedString(@"Twitter_update_successful", @"")
#define kTwitterUpdateSuccessfulDesctiption NSLocalizedString(@"Twitter_update_successful_Description", @"")

#define kTwitterUpdateFailure NSLocalizedString(@"Twitter_update_fail", @"")
#define kTwitterUpdateFailureDesctiption NSLocalizedString(@"Twitter_update_fail_Description", @"")

#define kPriceInvalid NSLocalizedString(@"Price_Invalid", @"")
#define kPriceInvalidDesctiption NSLocalizedString(@"Price_Invalid_description", @"")

#define kPurchaseProductDescriptionFirst NSLocalizedString(@"offers_you_a_special_discounted_price_of", @"")
#define kPurchaseProductDescriptionSecond NSLocalizedString(@"for_this_product", @"")
#define kPurchaseProductDescriptionUpdate NSLocalizedString(@"offers_you_special_coupons", @"")

#define kRejectButtonTitle NSLocalizedString(@"Reject", @"")
#define kAcceptButtonTitle NSLocalizedString(@"Accept", @"")

#define kUpdatePriceLblText NSLocalizedString(@"Please_Update_Price", @"")
#define kUpdatePriceError NSLocalizedString(@"Update_Price_Error", @"")
#define kRelatedProductsLblText NSLocalizedString(@"Related_Products", @"")

//Bar Code
#define kScanBtnTitle NSLocalizedString (@"Scan", "")
#define kBarcodeDisclaimer NSLocalizedString (@"Barcode_Camera_Orientation", "")

//Review
#define kPosted NSLocalizedString (@"Posted :", "")
#define kRating NSLocalizedString (@"Rating :", "")


//Profile  
//TextField Placeholdertext
#define kNamePlaceHolderText NSLocalizedString(@"Name_PlaceHolder_Text", @"")
#define kEmailPlaceHolderText NSLocalizedString(@"Email_PlaceHolder_Text", @"")
#define kPasswordPlaceHolderText NSLocalizedString(@"Password_PlaceHolder_Text", @"")
#define kConfirmPasswordPlaceHolderText NSLocalizedString(@"ConfirmPassword_PlaceHolder_Text", @"")
#define kAddressPlaceHolderText NSLocalizedString(@"Address_PlaceHolder_Text", @"")
#define kLogOutButtonText NSLocalizedString(@"LogOut_Button_Text", @"")

#define kNoProfileUpdationDescription NSLocalizedString(@"No_Profile_Updation_Description", @"")

#define kProfileSuccessful NSLocalizedString(@"Profile_Successful", @"")
#define kProfileSuccessfulDescription NSLocalizedString(@"Profile_Successful_Description", @"")

//Summary
#define kDescriptionTitle NSLocalizedString(@"Description", "")

//Favorite
#define kProductAddSuccessfulDescription NSLocalizedString(@"Product_Add_Successful_Description", @"")

#define kDeleteConfrmationDescription NSLocalizedString(@"Delete_confirmation_Description", @"")
#define kNoFavoritesAdded NSLocalizedString(@"No_Favorites_Added", @"")

//Coupon
#define kValidTill NSLocalizedString (@"valid_Till",@"")
#define kCouponCodeLabelTitle NSLocalizedString (@"Coupon_Code",@"")
#define kCouponDeleteConfirmation NSLocalizedString (@"Coupon_Delete_Confirmation",@"")
#define kNoCouponAvailable NSLocalizedString (@"No_Coupon_available",@"")
#define kEnterValidPrice NSLocalizedString (@"Please_enter_a_valid_price_to_update",@"")
