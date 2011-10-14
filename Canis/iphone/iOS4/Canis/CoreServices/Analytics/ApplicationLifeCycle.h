//
//  ApplicationLifeCycle.h
//  Ubira
//
//  Created by [Cybage Team] on 13/06/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>

@class Analytics;

@interface ApplicationLifeCycle : NSManagedObject {
@private
}
@property (nonatomic, retain) NSString * ApplicationLifeID;
@property (nonatomic, retain) NSSet* ApplicationLifeToAnyalitics;

- (void)addApplicationLifeToAnyaliticsObject:(Analytics *)value;

@end
