//
//  ValidationHandler.m
//  Ubira
//
//  Created by [Cybage Team]  on 16/05/11.
//  Copyright 2011 FreeCause All rights reserved.
//

#import "ValidationHandler.h"

@implementation ValidationHandler

/*!
 @function		emailValidate
 @abstract		email Validation
 @discussion	validate the email text entered by user.
                checks if ".,@" are present or not.
				checks for domain name should not contain two adjacent dot "." etc 
 @param         email - text entered by user
 @result		will retun the bool value for success or faliure of validation 
*/
+ (BOOL)emailValidate:(NSString *)email
{
    @try{
        //Based on the string below	
        //Quick return if @ Or . not in the string
        if([email rangeOfString:@"@"].location==NSNotFound || [email rangeOfString:@"."].location==NSNotFound)
            return NO;
        
        //Break email address into its components
        NSString *accountName=[email substringToIndex: [email rangeOfString:@"@"].location];
        email=[email substringFromIndex:[email rangeOfString:@"@"].location+1];
        
        if ( [[email componentsSeparatedByString:@"."] count] > 3)
        {
            return NO;
        }
        //’.’ not present in substring
        if([email rangeOfString:@"."].location==NSNotFound)
            return NO;
        NSString *domainName=[email substringToIndex:[email rangeOfString:@"."].location];
        NSString *subDomain=[email substringFromIndex:[email rangeOfString:@"."].location+1];
        
        if ([subDomain rangeOfString:@"."].location != NSNotFound)
        {
            NSString *firstCharacterOFsubDomain =[subDomain substringToIndex:[subDomain rangeOfString:@"."].location+1];
            NSString *lastCharacterOFsubDomain=[subDomain substringFromIndex:[subDomain rangeOfString:@"."].location+1];
            
            if ([firstCharacterOFsubDomain  isEqualToString:@"."] || [lastCharacterOFsubDomain isEqualToString:@""]) 
            {
                return NO;
            }
        }
        
        //username, domainname and subdomain name should not contain the following charters below
        //filter for user name
        NSString *unWantedInUName = @"~!@#$^&*()={}[]|;’:\"<>,?/`";
        //filter for domain
        NSString *unWantedInDomain = @" ~!@#$%^&*()={}[]|;’:\"<>,+?/`";
        //filter for subdomain
        NSString *unWantedInSub = @"`~!@#$%^&*()={}[]:\";’<>,?/1234567890";
        
        //subdomain should not be less that 2 and not greater 6
        if(!(subDomain.length>=2 && subDomain.length<=6)) return NO;
        
        if([accountName isEqualToString:@""] || 
           [accountName rangeOfCharacterFromSet:[NSCharacterSet characterSetWithCharactersInString:unWantedInUName]].location!=NSNotFound || [domainName isEqualToString:@""] || [domainName rangeOfCharacterFromSet:[NSCharacterSet characterSetWithCharactersInString:unWantedInDomain]].location!=NSNotFound || [subDomain isEqualToString:@""] || [subDomain rangeOfCharacterFromSet:[NSCharacterSet characterSetWithCharactersInString:unWantedInSub]].location!=NSNotFound)
            return NO;
        
        return YES;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception)
        return NO;
    }
}

/*!
 @function      checkSpecialCharacterForString
 @abstract      check if entered text is SpecialCharacter 
 @discussion	check if entered text is SpecialCharacter 
 @param         string   - entered text by user
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)checkSpecialCharacterForString:(NSString *)currString
{
    @try {
        NSString *unWantedInDomain = @"-.~!@#$%^&*()={}[]|;’:\"_\\'<>,+?/`";
        
        return ([currString rangeOfCharacterFromSet:[NSCharacterSet characterSetWithCharactersInString:unWantedInDomain]].location!=NSNotFound) ? YES : NO;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception)
        return NO;
    }
}

/*!
 @function      isSpaceOnly
 @abstract      check if entered text is SpecialCharacter 
 @discussion	check if entered text is SpecialCharacter 
 @param         string   - entered text by user
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)isSpace:(NSString *)currString
{
    @try {
        return ([currString isEqualToString:@" "]) ? YES : NO;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception)
        return NO;
    }
}

/*!
 @function		checkMaxLength
 @abstract		check for max legth of text
 @discussion	check if entered text has not exceeded it max length range 
 @param			maxLength - max range for text which should not exceed 
				string   - entered text by user
 @result		will retun the bool value for success or faliure of validation 
*/
+ (BOOL)checkMaxLength:(NSInteger)maxLength string:(NSString *)string
{
    @try {
        return ([string length]<=maxLength);
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception)
        return NO;
    }
}

/*!
 @function		checkMinLength
 @abstract		check for min legth of text
 @discussion	check if entered text is not less than min length range 
 @param			minLength - min range for text
				string   - entered text by user
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)checkMinLength:(NSInteger)maxLength string:(NSString *)string
{	
    @try {
        return ([string length]>=maxLength);
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception)
        return NO;
    }
}

/*!
 @function		passwordValidate
 @abstract		password Validation
 @discussion	validate the password text entered by user.
				checks the combination of text and number or text and symbol
 @param			password - text entered by user
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)passwordValidate:(NSString *)password
{
    @try {
        NSString *stricterFilterString = @"^.*(?=.{8,})(?=.*[a-zA-Z])(?=.*[0-9@#$%&_]).*$";
        
        NSPredicate *emailTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", stricterFilterString];
        
        return [emailTest evaluateWithObject:password];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception) 
        return NO;
    }
}

/*!
 @function		phoneValidate
 @abstract		phone Validation
 @discussion	validate the phone text entered by user.
				checks phone text should contain only numeric
 @param			phone - text entered by user
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)phoneValidate:(NSString *)phoneNumber
{
    @try {
        BOOL stricterFilter = YES; 
        NSString *stricterFilterString = @"([0-9]+)";
        NSString *laxString = @".+@.+\\.[A-Za-z]{2}[A-Za-z]*";
        
        NSString *phoneRegex = stricterFilter ? stricterFilterString : laxString;
        NSPredicate *phoneTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", phoneRegex];
        return [phoneTest evaluateWithObject:phoneNumber];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception) 
        return NO;
    }
}

/*!
 @function		trimCurrentString
 @abstract		trim string by removing space at the end 
 @discussion	trim string by removing space at the end 
 @param			string - Which needs to trim
 @result		NSString - will return trimmed string
 */
+ (NSString *)trimCurrentString:(NSString *)givenString
{
    @try {
        return (givenString)?([givenString stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]]) : givenString;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception) 
        return givenString;
    }
}

/*!
 @function		checkSpaceOrNewLineCharacter
 @abstract		check space or newLineCharacter from string
 @discussion	check space or newLineCharacter from string
 @param			string - Which needs to check for space and newLineCharacter
 @result		BOOL - will retun the bool value for success or faliure of validation
 */
+ (BOOL)checkSpaceOrNewLineCharacter:(NSString *)inputString
{
    @try {
        NSString* postText = inputString;
        
        if(postText)
        {
            if([postText length] == 0) return NO;
            
            NSString *spaceRegex = @"\\s{1,}";            
            NSPredicate *phoneTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", spaceRegex];
            
            if (![phoneTest evaluateWithObject:postText])
            {
                return YES;
            }  
        }
        return NO;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception) 
        return NO;
    }
}

/*!
 @function		priceValidate
 @abstract		price Validation
 @discussion	checks phone text should contain only numeric with or without decimal
 @result		will retun the bool value for success or faliure of validation 
 */
+ (BOOL)priceValidate:(NSString *)price
{	
    @try {
        NSString *phoneRegex = @"([0-9]*)(.?)([0-9]*)";
        NSPredicate *phoneTest = [NSPredicate predicateWithFormat:@"SELF MATCHES %@", phoneRegex];
        return [phoneTest evaluateWithObject:price];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception) 
        return NO;
    }
}

@end