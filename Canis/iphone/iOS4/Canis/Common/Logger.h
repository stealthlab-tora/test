
#import <Foundation/Foundation.h>

#ifndef TRC_LEVEL
#if DEBUG
#define TRC_LEVEL 0 
#else
#define TRC_LEVEL 4
#endif
#endif


/*****************************************************************************/ 
/* Entry/exit trace macros                                                   */ 
/*****************************************************************************/ 
#if TRC_LEVEL == 0 
#define TRC_ENTRY    NSLog(@"ENTRY: %s:%d:", __PRETTY_FUNCTION__,__LINE__); 
#define TRC_EXIT     NSLog(@"EXIT:  %s:%d:", __PRETTY_FUNCTION__,__LINE__); 
#else 
#define TRC_ENTRY 
#define TRC_EXIT 
#endif 
 
/*****************************************************************************/ 
/* Debug trace macros                                                        */ 
/*****************************************************************************/ 
#if (TRC_LEVEL <= 1) 
#define TRC_DBG(A, ...) NSLog(@"DEBUG: %s:%d:%@", __PRETTY_FUNCTION__,__LINE__,[NSString stringWithFormat:A, ## __VA_ARGS__]); 
#else 
#define TRC_DBG(A, ...) 
#endif 

#if (TRC_LEVEL <= 2) 
#define TRC_INFO(A, ...) NSLog(@"INFORMATION:%s:%d:%@", __PRETTY_FUNCTION__,__LINE__,[NSString stringWithFormat:A, ## __VA_ARGS__]); 
#else 
#define TRC_INFO(A, ...) 
#endif 

#if (TRC_LEVEL <= 3) 
#define TRC_WARNING(A, ...) NSLog(@"WARNING: %s:%d:%@", __PRETTY_FUNCTION__,__LINE__,[NSString stringWithFormat:A, ## __VA_ARGS__]); 
#else 
#define TRC_WARNING(A, ...) 
#endif 

#if (TRC_LEVEL <= 4) 
#define TRC_ERR(A, ...) NSLog(@"ERROR: %s:%d:%@", __PRETTY_FUNCTION__,__LINE__,[NSString stringWithFormat:A, ## __VA_ARGS__]); 
#define TRC_EXCEPTION(A)    [Logger logMe:A functionName:[NSString stringWithUTF8String: __PRETTY_FUNCTION__] lineNumber:__LINE__];
#else 
#define TRC_ERR(A, ...) 
#define TRC_EXCEPTION(A)
#endif

#define LOG_FILENAME @"UbiraLog.log"

@interface Logger : NSObject {
}
+ (void)logMe:(NSException*) exception functionName:(NSString*) functionName lineNumber:(int) line;

@end


