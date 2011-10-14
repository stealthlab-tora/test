//
//  ValidationHandler.h
//  Ubira
//
//  Created by [Cybage Team]  on 16/05/11.
//  Copyright 2011 FreeCause All rights reserved.
//

#import <Foundation/Foundation.h>

/*!
    @class       ValidationHandler
    @abstract    handles the validation
    @discussion  handles the various validation for textFields
*/

@interface ValidationHandler : NSObject 
{

}

+ (BOOL)emailValidate:( NSString *)email;
+ (BOOL)checkMaxLength:(NSInteger)maxLength string:(NSString *)string;
+ (BOOL)checkMinLength:(NSInteger)minLength string:(NSString *)string;
+ (BOOL)passwordValidate:(NSString *)password;
+ (BOOL)phoneValidate:(NSString *)phoneNumber;
+ (BOOL)checkSpecialCharacterForString:(NSString *)currString;
+ (BOOL)checkSpaceOrNewLineCharacter:(NSString *)inputString;
+ (BOOL)isSpace:(NSString *)currString;
+ (NSString *)trimCurrentString:(NSString *)string;
+ (BOOL)priceValidate:(NSString *)price;

@end
