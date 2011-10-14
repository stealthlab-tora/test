
#import <Foundation/Foundation.h>

// Dependant upon GTMNSString+HTML

@interface NSString (HTML)

- (NSString *)stringByDecodingXMLEntities;

+ (NSString *)GetUUID;
@end
