//
//  ImageDownloadQueue.h
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>

/*!
@class			ImageDownloadQueue
@abstract		An queue to download images asynchronously.
@discussion		Class is used to queue the NSOpearations which will download images from 
                server asynchronously.
*/

@interface ImageDownloadQueue : NSObject {

	NSOperationQueue  *_downloadQueue;
	int				  operationsCount;
    int               numOfConcurrentDownloads;
	
}

@property(nonatomic, assign) NSOperationQueue   *downloadQueue;
@property(nonatomic, assign) int                operationsCount;
@property(nonatomic, assign) int                numOfConcurrentDownloads;

+ (ImageDownloadQueue*)sharedQueue;

- (void)setNumOfConcurrentDownloads:(int)concurrentDownloads;
- (void)addOperation:(NSOperation*)operation;
- (void)cancelAllOperations;

@end
