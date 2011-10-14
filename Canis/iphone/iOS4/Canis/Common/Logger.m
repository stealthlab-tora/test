//
//  Logger.m
//  Ubira
//
//  Created by [Cybage Team] on 10/08/11.
//  Copyright 2011 FreeCause All rights reserved.
//

#include "Logger.h"

static NSString *logFilePath = nil;
static NSString *logPath = nil;
static NSString *deviceDetails = nil;
 
@interface Logger (PrivateMethods)

/**
 *	@functionName	: GetLogFilePath
 *	@parameters		: void
 *	@return			: NSString - Full log file path
 *	@description	: This method return log file path.
 *					  If file is not present it will create that file in applicaiton document directory.
 *					  Also will create the directory structure if missing in application document directory.
 */
+ (NSString *) getLogFilePath;

/**
 *	@functionName	: SetPath
 *	@parameters		: (NSString*) path - log file path.
 *	@return			: void
 *	@description	: This method set the path for the file in which logging should be done.
 */
+ (void) setPath : (NSString *)path;



@end

@implementation Logger

/**
 *	@functionName	: GetLogFilePath
 *	@parameters		: void
 *	@return			: NSString - Full log file path
 *	@description	: This method return log file path.
 *					  If file is not present it will create that file in applicaiton
                      document directory.
 *                    Also will create the directory structure if missing in application  
                      document directory.
 */
+ (NSString *) getLogFilePath
{
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES);
	
	NSString *documentsDirectoryPath = [paths objectAtIndex:0];
	
	//if no log file path is found set it to default
	if(!logPath)
	{
		//if no log file path is found set it to default
		[self setPath:@""];
	}
    
	NSFileManager *manager = [NSFileManager defaultManager];
	
    
	NSString *documentDirPath = [documentsDirectoryPath stringByAppendingPathComponent:[logPath lastPathComponent]];
	
	//check - log file exist
	if(![manager fileExistsAtPath:documentDirPath])
	{
		//if not create a new log file
		[manager createFileAtPath:documentDirPath contents:nil attributes:nil];
	}
	//set the log file path to provided file structure
	if(!logFilePath)
	{
		logFilePath = [[NSString alloc] initWithString:documentDirPath] ;
	}
	return nil;
}

/**
 *	@functionName	: SetPath
 *	@parameters		: (NSString*) path - log file path.
 *	@return			: void
 *	@description	: This method set the path for the file in which logging should be done.
 */
+ (void) setPath : (NSString *)path
{
	//check is path already set
	if(!logPath)
	{
		logPath = [[NSString alloc] initWithString:LOG_FILENAME];
		//check path is nil 
		if(path && ![path isEqualToString:@""])
		{
			logPath = [NSString stringWithString:path];
		}
	}
}

/**
 *	@functionName	: logMe
 *	@param          : (NSException*) exception
 *	@return			: void
 *	@description	: 
 */
+ (void)logMe:(NSException*) exception functionName:(NSString*) functionName lineNumber:(int) line
{
    @try 
	{					
        //check is log file path is set
        if(!logFilePath)
        {
            //set the log file path to default
            [Logger getLogFilePath];
        }
        
        NSDate *now = [NSDate date];
        
        NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
        [formatter setTimeZone:[NSTimeZone timeZoneWithAbbreviation:@"GMT"]];
        
        [formatter setDateFormat:@"dd MMM yyyy"];
        NSString *theDateString = [formatter stringFromDate:now];            
        
        [formatter setDateFormat:@"HH:mm:ss:ms"];
        NSString *theTimeStirng = [formatter stringFromDate:now];
        
        [formatter release];
        
        if (!deviceDetails) {
            deviceDetails = [[NSString alloc]initWithFormat:@"%@ - %@ - %@ - ",[[UIDevice currentDevice] uniqueIdentifier],[[UIDevice currentDevice] model],[[UIDevice currentDevice] systemVersion]];
        }
        
        @synchronized(self){            
            NSFileHandle *fileHandler = [NSFileHandle fileHandleForWritingAtPath:logFilePath];
            if(!fileHandler){
                return;
            }
            
            [fileHandler seekToEndOfFile];            
        
            NSString *logString = [[NSString alloc] initWithFormat:@"\n%@%@ %@ %@ - %d \nDescription :  %@#", deviceDetails,theDateString, theTimeStirng, functionName,line, exception];        
            NSData *data = [logString dataUsingEncoding:NSASCIIStringEncoding];
            [logString release];
            
            [fileHandler writeData:data];            
            [fileHandler closeFile];
        }
	}
	@catch (NSException * e) 
	{
		NSLog(@"Unable to log the message - %@", [e description]);
	}
}



@end
