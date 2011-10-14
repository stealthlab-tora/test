//
//  LocationController.h
//  Ubira
//
//  Created by [Cybage Team] on 06/05/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <CoreLocation/CoreLocation.h>
#import <Foundation/Foundation.h>


@protocol LocationManagerDelegate <NSObject>

- (void)locationUpdateSuccess:(NSError*) error;

@end

/*!
    @class			LocationController
    @abstract		Class provides way to getting current geo location.
    @discussion		This class exploses methods which gives current geo location and 
					region monitoring.
*/

#define kLocationServiceError @"kLocationServiceError"
#define kLocationServiceSuccess @"kLocationServiceSuccess"

@interface  LocationManager : NSObject <CLLocationManagerDelegate> {
    CLLocationManager   *locationManager;
    CLLocation          *currentLocation;

    id<LocationManagerDelegate>     _delegate;
    
    BOOL                onceLocationUpdated;

}

//@property (nonatomic, retain) CLLocation *currentLocation;
@property (nonatomic, assign) id <LocationManagerDelegate>   delegate;
@property (nonatomic, readonly) BOOL      onceLocationUpdated;

+ (LocationManager *)sharedInstance;

- (void)start;
- (void)stop;
- (BOOL)locationKnown;
- (BOOL)regionMonitoringAvailable;
- (BOOL)regionMonitoringEnabled;
- (void)locationManagerStartMonitoringRegion:(CLRegion *)region withAccuracy:(CLLocationAccuracy)            accuracy;
- (void)locationManagerStopMonitoringRegion:(CLRegion *)region;
- (void)setDefaultLocation:(float) latitude longitude:(float) longitude;
- (BOOL)defaultLocationSetting:(float*) latitude longitude:(float*) longitude;
- (CLLocation*)currentLocation;
@end