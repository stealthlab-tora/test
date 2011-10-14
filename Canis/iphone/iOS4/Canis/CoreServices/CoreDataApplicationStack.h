//
//	CoreDataApplicationStack.h
//	
//
//	Created by [Cybage Team] on 5/16/11.
//	Copyright 2011 FreeCause. All rights reserved.
//

#import <Foundation/Foundation.h>

/*!
    @class          CoreDataApplicationStack
    @abstract       This class handles Core Data managment.  
    @discussion     This class has core data context, coordinator and model which
                    will be used in  core data management.
*/

@interface CoreDataApplicationStack : NSObject {
	
	NSPersistentStoreCoordinator *persistentStoreCoordinator;
	NSManagedObjectModel         *managedObjectModel;
	NSManagedObjectContext       *managedObjectContext;
    NSManagedObjectContext       *addingManagedObjectContext;
    NSManagedObjectContext       *analyticsManagedObjectContext;
}

+ (CoreDataApplicationStack*)globalStack;
- (BOOL)commitData;
- (BOOL)commitAnyaliticsData;

@property (nonatomic, readonly) NSManagedObjectContext *managedObjectContext;
@property (nonatomic, readonly) NSManagedObjectContext *addingManagedObjectContext;
@property (nonatomic, readonly) NSManagedObjectContext *analyticsManagedObjectContext;

@end
