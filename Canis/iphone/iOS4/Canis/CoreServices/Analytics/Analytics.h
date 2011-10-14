//
//  Anyalitics.h
//  Ubira
//
//  Created by [Cybage Team] on 13/06/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>


@interface Analytics : NSManagedObject {
@private
}

@property (nonatomic, retain) NSString * ScreenID;
@property (nonatomic, retain) NSDate * StartDate;
@property (nonatomic, retain) NSDate * EndDate;
@property (nonatomic, retain) NSManagedObject * AnyaliticsToApplicationLifeCycle;

@end
