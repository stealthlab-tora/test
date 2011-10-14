//
//  LocationController.m
//  Ubira
//
//  Created by [Cybage Team] on 06/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "LocationManager.h"

@implementation LocationManager

//@synthesize currentLocation;
@synthesize delegate =_delegate;
@synthesize onceLocationUpdated;

const float kZeroFloatValue = 0.0f;

static LocationManager *sharedInstance;


/*!
 @function		sharedInstance
 @abstract		give LocationController shard instance.
 @discussion	This is singleton implementaion of LocationController provides 
                shared instance.
 @param			none 
 @result		LocationController
 */

+ (LocationManager *)sharedInstance {
    
    @try {
        @synchronized(self) {
            if (!sharedInstance)
                sharedInstance=[[LocationManager alloc] init];       
        }       
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception);
        sharedInstance =nil;
	}
   
    return sharedInstance;
}



/*!
 @function		alloc
 @abstract		Will allocation memory to shared instance of LocationController class.
 @discussion	Will allocation memory to shared instance of LocationController class,
                if not allocted.
 @param			none 
 @result		id 
 */
+ (id)alloc {
    @try {
        @synchronized(self) {
            NSAssert(sharedInstance == nil, @"Attempted to allocate a second instance of a singleton LocationController.");
            sharedInstance = [super alloc];
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
        sharedInstance = nil;
	}
    
    return sharedInstance;
}

/*!
 @function		init
 @abstract		init LocationController
 @discussion	This function initializes CLLocationManager object.
 @param			none 
 @result		id ehich is self of LocationController
 */
- (id)init {
    @try {
        if ((self = [super init])) {
            locationManager = [[CLLocationManager alloc] init];
            locationManager.delegate = self;
            
            onceLocationUpdated = FALSE;
            self.delegate = nil;
            [self setDefaultLocation:kZeroFloatValue longitude:kZeroFloatValue];
            
            [self start];
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception);
        locationManager = nil;
        currentLocation = nil;
	}
    
    return self;
}

/*!
 @function		start
 @abstract		function registers to get location updates.
 @discussion	This function register itself to get location.
 @param			none 
 @result		void
 */
- (void)start {
    #if TARGET_IPHONE_SIMULATOR
        onceLocationUpdated = TRUE;
    #else
        [locationManager startUpdatingLocation];
    #endif
}

/*!
 @function		stop
 @abstract		stops updating location.
 @discussion	This function cancels registration for updating location.
 @param			none 
 @result		void
 */
- (void)stop {
    [locationManager stopUpdatingLocation];
}

/*!
 @function		locationKnown
 @abstract		Determins whether manager got location or not.
 @discussion	Determins whether manager got location or not.
 @param			none 
 @result		bool
 */
- (BOOL)locationKnown { 
    
    BOOL locationServiceStatus = NO;
    
    @try {
        if(![CLLocationManager locationServicesEnabled])
        {
            return locationServiceStatus;
        }
        
        if( [[CLLocationManager class] resolveClassMethod:@selector(authorizationStatus)] )
        {
            switch ([CLLocationManager authorizationStatus]) 
            {
                case kCLAuthorizationStatusNotDetermined: // User has not yet made a choice with regards to this application
                    break;
                case kCLAuthorizationStatusRestricted:  // This application is not authorized to use location services.  Due
                    break;                              // to active restrictions on location services, the user cannot change
                    // this status, and may not have personally denied authorization
                    
                case kCLAuthorizationStatusDenied:  // User has explicitly denied authorization for this application, or
                    break;                          // location services are disabled in Settings
                    
                case kCLAuthorizationStatusAuthorized: // User has authorized this application to use location services
                    locationServiceStatus = YES;
                    break;
            }
        }
        else
        {
            locationServiceStatus = YES;
        }        
	}
	@catch (NSException * exception) {
		TRC_EXCEPTION(exception); 
        locationServiceStatus = NO;
	}
    
   return locationServiceStatus;
}

#pragma mark- Region Monitoring
/*!
 @function		locationManagerStopMonitoringRegion
 @abstract		Stop region monitoring. 
 @discussion	Stop region monitoring.
 @param			CLRegion - Region for which needs to stop monitoring
 @result		void
 */
- (void)locationManagerStopMonitoringRegion:(CLRegion *)region 
{    
    TRC_DBG(@"Stop Monitoring");  
    [locationManager stopMonitoringForRegion:region];
    TRC_DBG(@"Monitored Regions: %i", [[locationManager monitoredRegions] count]); 
} 

/*!
 @function		locationManagerStartMonitoringRegion
 @abstract		Determins whether region monitoring is available on device or not.
 @discussion	Determins whether region monitoring is available on device or not.
 @param			CLRegion - Region on which needs to do monitoring
                CLLocationAccuracy - Accuracy for filtering the boundy areas
 @result		void
 */
- (void)locationManagerStartMonitoringRegion:(CLRegion *)region withAccuracy:(CLLocationAccuracy)accuracy
{    
    TRC_DBG(@"Start Monitoring");  
    [locationManager startMonitoringForRegion:region desiredAccuracy:accuracy];  
   
    
    TRC_DBG(@"Monitored Regions: %i", [[locationManager monitoredRegions] count]); 
} 

/*!
 @function		regionMonitoringAvailable
 @abstract		Determins whether region monitoring is available on device or not.
 @discussion	Determins whether region monitoring is available on device or not.
 @param			none 
 @result		bool
 */
- (BOOL)regionMonitoringAvailable
{
    return ([CLLocationManager regionMonitoringAvailable]);
}

/*!
 @function		regionMonitoringAvailable
 @abstract		Determins whether region monitoring is enabled on device or not.
 @discussion	Determins whether region monitoring is enabled on device or not.
 @param			none 
 @result		bool
 */
- (BOOL)regionMonitoringEnabled
{
    return ([CLLocationManager regionMonitoringEnabled]);
}

/*!
 @function		didExitRegion
 @abstract		Determins whether monitoredregion is exited.
 @discussion	Determins whether monitoredregion is exited.
 @param			CLRegion - cordinates of region exited. 
 @result		void
 */
- (void)locationManager:(CLLocationManager *)manager didExitRegion:(CLRegion *)region
{
    [[NSNotificationCenter defaultCenter] postNotificationName:kRegionExitNotification object:nil];
}

/*!
 @function		locationManager monitoringDidFailForRegion
 @abstract		This is call back method if manager got fail to monitoring region.
 @discussion	This is call back method when CLLocation manager failed to monitoring region.
                This will also return monitored region value.
 @param			manager		-	CLLocationmanger object
 @param			region	- region which gets monitored
 @param			error	- error discription for failure	
 @result		void
 */
- (void)locationManager:(CLLocationManager *)manager monitoringDidFailForRegion:(CLRegion *)region withError:(NSError *)error
{
    [self locationManagerStopMonitoringRegion:region];
}

/*!
 @function		locationManager
 @abstract		This is call back method if manager got new location.
 @discussion	This is call back method when CLLocation manager fetched new location value.
				This will also return old location value.
 @param			manager		-	CLLocationmanger object
 @param			newLocation	-	
 @param			oldLocation	-	
 @result		void
 */
- (void)locationManager:(CLLocationManager *)manager didUpdateToLocation:(CLLocation *)newLocation fromLocation:(CLLocation *)oldLocation {
    //if the time interval returned from core location is more than two minutes we ignore it because it might be from an old session
    if ( abs([newLocation.timestamp timeIntervalSinceDate: [NSDate date]]) < 120 ) {             
            [self setDefaultLocation:newLocation.coordinate.latitude longitude:newLocation.coordinate.longitude];
    }
    
    if (!onceLocationUpdated) {
        [self setDefaultLocation:newLocation.coordinate.latitude longitude:newLocation.coordinate.longitude];
        onceLocationUpdated = TRUE;
    }
    
    if([self.delegate respondsToSelector:@selector(locationUpdateSuccess:)]){
        [self.delegate locationUpdateSuccess:nil];
        self.delegate = nil;
    }else{
        self.delegate = nil;
    }
    
    
}

/*!
 @function		locationManager
 @abstract		This is call back method if error occured.
 @discussion	This is call back method if error occured while fetching current 
                geo location.
 @param			manager		-	CLLocationmanger object
 @param			error		-	Error object having code description in it.
 @result		void
 */
- (void)locationManager:(CLLocationManager *)manager didFailWithError:(NSError *)error {  
    
    // Temporary set default location lat and long as hardcoded.
    [self setDefaultLocation:kZeroFloatValue longitude:kZeroFloatValue];
	
    if (!onceLocationUpdated) {      
        onceLocationUpdated = TRUE;
    }
    
    //Handle Error
    TRC_ERR(@"Location Manager Error %@", [error description])
  
    if([self.delegate respondsToSelector:@selector(locationUpdateSuccess:)]){
        [self.delegate locationUpdateSuccess:error];
        self.delegate = nil;
    }else{
        self.delegate = nil;
    }
}

- (void)setDefaultLocation:(float) latitude longitude:(float) longitude
{    
    float def_latitude = latitude;
    float def_longitude = longitude;
    
    [self defaultLocationSetting:&def_latitude longitude:&def_longitude];
        
    if (currentLocation) {
        [currentLocation release];        
    }
    currentLocation = [[CLLocation alloc] initWithLatitude:def_latitude longitude:def_longitude];
}

- (BOOL)defaultLocationSetting:(float*) latitude longitude:(float*) longitude
{
    NSUserDefaults *defaults = [NSUserDefaults standardUserDefaults];
    BOOL defaultLocationEnabled = [defaults boolForKey:@"defaultLocation"];
    
    float lat = [[defaults valueForKey:@"latitude"] floatValue];
    float longi = [[defaults valueForKey:@"longitude"] floatValue]; 
    
    if (defaultLocationEnabled && latitude && longitude ){
        *latitude = lat;
        *longitude = longi;
        return TRUE;     
    }else{
        return FALSE;
    }
    
}

- (CLLocation*)currentLocation
{
    [self setDefaultLocation:currentLocation.coordinate.latitude longitude:currentLocation.coordinate.longitude];
    return currentLocation;
}

@end