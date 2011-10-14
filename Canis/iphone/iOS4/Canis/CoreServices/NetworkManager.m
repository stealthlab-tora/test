//
//  ReachabilityManger.m
//  Ubira
//
//  Created by [Cybage Team] on 05/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "NetworkManager.h"
#import "Reachability.h"

static NetworkManager* networkManager = nil;

@implementation NetworkManager

@synthesize reachableStaus;
@synthesize activeConnections;

/*!
 @function sharedManager
 @abstract create and return a shared instance
 @discussion create and return a shared instance of network manager.
 @param nil.
 */
+ (id)sharedManager
{
    @try {
        @synchronized(self)
        {
            if (networkManager == nil) {
                networkManager = [[self alloc]init];
            }
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
        networkManager = nil;
	}
	return networkManager;
}

/*!
 @function      networkAvailable
 @abstract      will check for the network availabilty status
 @discussion    will check for the network availabilty status for wifi and cellular
                network and return the status.
 @param         Reachability for which we want to check the availability.
 @result        void
 */
- (BOOL)networkAvailable:(Reachability*)currentReachablity
{
    BOOL connectionRequired = NO;
    NetworkStatus netStatus = [currentReachablity currentReachabilityStatus];
    switch (netStatus)
    {
        case NotReachable:
        {
            //Access Not Available
            //Minor detail- connectionRequired may return yes, even when the host is unreachable.  
            //We cover that up here...
            connectionRequired= NO;  
            break;
        }
        case ReachableViaWWAN:
        {
            //Reachable WWAN
            connectionRequired = YES;
            
            break;
        }
        case ReachableViaWiFi:
        {
            //Reachable WiFi
            connectionRequired = YES;
            break;
        }
    }
    return connectionRequired;
}

/*!
 @function      isReachable
 @abstract      will return the current status of reachability
 @discussion    will return the current status of reachability set on the callback.
 @param         nil
 @result        void
 */
- (bool)isReachable
{
    return reachableStaus;
}

/*!
 @function      startNotifier
 @abstract      start the notifier on the connectivity
 @discussion    start the notifier on the connectivity.
 @param         nil
 @result        return YES or NO
 */
- (BOOL)startNotifier
{
	BOOL retVal = NO;
    
    @try {
		// Observe the kNetworkReachabilityChangedNotification. When that notification is
        // posted method "reachabilityChanged" will be called. 
        [[NSNotificationCenter defaultCenter] addObserver: self selector: @selector(reachabilityChanged:) name: kReachabilityChangedNotification object: nil];
        
        internetReach = [[Reachability reachabilityForInternetConnection] retain];
        [internetReach startNotifier];
        
        wifiReach = [[Reachability reachabilityForLocalWiFi] retain];
        [wifiReach startNotifier];
        
        //For initial setup for network checking
        if(internetReach && wifiReach)
        {
            reachableStaus= NO;
            if ([self networkAvailable:wifiReach] || [self networkAvailable:internetReach])
            {
                //WiFi or Cellular network is available
                reachableStaus= YES;
            }
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
	}
    
	return retVal;
}

/*!
 @function      reachabilityChanged
 @abstract      called by Reachability whenever status changes
 @discussion    called by Reachability whenever status changes.
 @param         NSNotification
 @result        nil
 */
- (void)reachabilityChanged:(NSNotification*)note
{
	Reachability* curReach = [note object];
	NSParameterAssert([curReach isKindOfClass: [Reachability class]]);
    
    // Reachablity is changed so check for the wifi and cellular network is any on is available
    reachableStaus= NO;
    
    if ([self networkAvailable:wifiReach] || [self networkAvailable:internetReach])
    {
        //WiFi or Cellular network is available
        reachableStaus= YES;
    }
}

/*!
 @function      stopNotifier
 @abstract      stop the notifier on the connectivity
 @discussion    stop the notifier on the connectivity.
 @param         nil
 @result        void
 */
- (void)stopNotifier
{
    [internetReach stopNotifier];
    [wifiReach stopNotifier];
}

/*!
 @function      addToActiveConnections
 @abstract      increase the activity indicator visibility count
 @discussion    increase the activity indicator visibility count.
 @param         void
 @result        void
 */
- (void)addToActiveConnections
{
    ++networkManager.activeConnections;
    if (networkManager.activeConnections == 1 ) {
        [UIApplication sharedApplication].networkActivityIndicatorVisible = YES;	
    }
}

/*!
 @function      releaseFromActiveConnections
 @abstract      decrease the activity indicator visibility count
 @discussion    decrease the activity indicator visibility count.
 @param         void
 @result        void
 */
- (void)releaseFromActiveConnections
{
	if( (--networkManager.activeConnections) <= 0 )
	{
		[UIApplication sharedApplication].networkActivityIndicatorVisible = NO;		
	}	
}

- (void)dealloc
{
	[self stopNotifier];
    internetReach = nil;
    wifiReach = nil;
	[super dealloc];
}

@end
