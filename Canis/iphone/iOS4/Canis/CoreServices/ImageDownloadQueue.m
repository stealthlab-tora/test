//
//  ImageDownloadQueue.m
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "ImageDownloadQueue.h"
#import "NetworkManager.h"

const int defaultConcurrentDownloads = 5;

//global instance of downloadQueue
static ImageDownloadQueue *imageQueue = nil;

@implementation ImageDownloadQueue

@synthesize downloadQueue = _downloadQueue;
@synthesize operationsCount;
@synthesize numOfConcurrentDownloads;

/*!
 @function		sharedQueue
 @abstract		gives shared instance of ImageDownloadQueue
 @discussion	This class will be singleton implemenation of ImageDownloadQueue.
 @param			none 
 @result		object of ImageDownloadQueue class.
 */
+ (ImageDownloadQueue*)sharedQueue
{
    @try {
        if (imageQueue == nil) {
            
            imageQueue = [[super allocWithZone:NULL] init];
            imageQueue.downloadQueue = [[NSOperationQueue alloc] init];
            [imageQueue.downloadQueue setMaxConcurrentOperationCount:defaultConcurrentDownloads];
        }
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
        imageQueue = nil;
	}
	return imageQueue; 
}

/*!
 @function		allocWithZone
 @abstract   
 @discussion 
 @param			zone	-	this zone which is NSZone object.
 @result		returns self.
 */
+ (id)allocWithZone:(NSZone *)zone
{
    return [[self sharedQueue] retain];
}

/*!
 @function		copyWithZone
 @abstract   
 @discussion 
 @param			zone	-	this zone which is NSZone object.
 @result		returns self.
 */
- (id)copyWithZone:(NSZone *)zone
{
    return self;
}

/*!
 @function		retain
 @abstract   
 @discussion 
 @param			none 
 @result		id which self pointer
 */
- (id)retain
{
    return self;
}

/*!
 @function		retainCount
 @abstract		denotes an object that cannot be released
 @discussion	denotes an object that cannot be released
 @param			none 
 @result		integer value having retiain count.
 */
- (NSUInteger)retainCount
{
    return NSUIntegerMax;  //denotes an object that cannot be released
}

/*!
 @function		release
 @abstract		
 @discussion	
 @param			none 
 @result		void
 */
- (void)release
{
    //do nothing
}

/*!
 @function		autorelease
 @abstract		
 @discussion	
 @param			none 
 @result		returns self pointer.
 */
- (id)autorelease
{
    return self;
}

/*!
 @function		setNumOfConcurrentDownloads
 @abstract		set the concurrent download number.
 @discussion	Using this fucntion, can control number of concurrent downloads. 
 @param			count of concurrentDownloads 
 @result		void
 */
-(void)setNumOfConcurrentDownloads:(int)concurrentDownloads
{
    @try {
         [imageQueue.downloadQueue setMaxConcurrentOperationCount:concurrentDownloads ? concurrentDownloads : defaultConcurrentDownloads];        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}
   
}

/*!
 @function		addOperation
 @abstract		Adds opeartion in operation queue.
 @discussion	This function adds image download operation in operation queue.
 @param			operation	-	this is NSOperation object as parameter.
 @result		void
 */
- (void)addOperation:(NSOperation*)operation
{
	@try {
        //Bump the operation count by one
		++imageQueue.operationsCount;
        //add the operation to queue
		[imageQueue.downloadQueue addOperation:operation];
	}
	@catch (NSException * exception) {		
		TRC_EXCEPTION(exception); 	
	}
}

/*!
 @function		cancelAllOperations
 @abstract		function cancles all outstanding operations.
 @discussion	This function cancels all outstanding opeations
 @param			none 
 @result		void
 */
- (void)cancelAllOperations
{
	@try {
        //Can all outstanding operations
		[imageQueue.downloadQueue cancelAllOperations];        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception);
	}
}

@end
