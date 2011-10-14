//
//	CoreDataApplicationStack.m
//	
//
//	Created by [Cybage Team] on 5/16/11.
//	Copyright 2011 FreeCause. All rights reserved.
//

#import "CoreDataApplicationStack.h"

static CoreDataApplicationStack *globalInstance = nil;

@implementation CoreDataApplicationStack

/*!
 @function      globalStack
 @abstract      This fucntion will return coredata stack object.
 @discussion    This is static function used in singleton implementation.
 @param         none 
 @result     
*/

+ (CoreDataApplicationStack*)globalStack
{
	if (globalInstance == nil) {
	   globalInstance = [[super allocWithZone:NULL] init];
	}
	return globalInstance;	
}

/*!
 @function      allocWithZone
 @abstract   
 @discussion 
 @param         none 
 @result     
 */
+ (id)allocWithZone:(NSZone *)zone
{
    return [[self globalStack] retain];
}

/*!
 @function      allocWithZone
 @abstract   
 @discussion 
 @param         none 
 @result     
 */
- (id)copyWithZone:(NSZone *)zone
{
    return self;
}

/*!
 @function      allocWithZone
 @abstract   
 @discussion 
 @param         none 
 @result     
 */
- (id)retain
{
    return self;
}

/*!
 @function		allocWithZone
 @abstract		Will return retain count
 @discussion	Will return retain count
 @param			none 
 @result		will return integer value which is retain count of this singleton object.
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
 @result     
 */
- (void)release
{
    //do nothing
}

/*!
 @function		autorelease
 @abstract		Will return self pointer.
 @discussion 
 @param			none 
 @result     
 */    
- (id)autorelease
{
    return self;
}

/*!
 @function		dealloc
 @abstract		release data member variables
 @discussion	release data member variables
 @param			none
 @result		void
 */
- (void)dealloc
{
	[managedObjectContext release];
    [addingManagedObjectContext release];
	[managedObjectModel release];
	[super dealloc];
}

/*!
 @function		documentsFolder
 @abstract   
 @discussion 
 @param			none 
 @result		return path of application's document folder.
 */
- (NSString*)documentsFolder
{
    NSString *filePath = nil;
    @try {
        NSArray *paths = NSSearchPathForDirectoriesInDomains(NSLibraryDirectory, 
                                                             NSUserDomainMask, YES);
        filePath = [paths objectAtIndex:0];
        
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
    }
    return filePath;
}


/*!
 @function		managedObjectModel
 @abstract		Returns the object model.
 @discussion	This function will create object model from url.
 @param			none 
 @result		return NSManagedObjectModel object.
 */
- (NSManagedObjectModel*)managedObjectModel 
{
	if (managedObjectModel) return managedObjectModel;
	
    @try {
        //get path for managed object model
        NSString *path = [[NSBundle mainBundle] pathForResource:@"Ubira"
                                                         ofType:@"momd"];
        if (!path) {
            path = [[NSBundle mainBundle] pathForResource:@"Ubira"
                                                   ofType:@"mom"];
        }
        
        NSAssert(path != nil, @"Unable to find DataModel in main bundle");
        NSURL *url = [NSURL fileURLWithPath:path];
        
        //load the managed object model
        managedObjectModel = [[NSManagedObjectModel alloc] initWithContentsOfURL:url];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        managedObjectModel = nil;
    }
  	return managedObjectModel;
}

/*!
 @function		persistentStoreCoordinator
 @abstract		Returns the persistent coordinator
 @discussion	This function will create persistent coordinator wich is linked to database 
                file.
 @param			none 
 @result		return NSPersistentStoreCoordinator object
 */
- (NSPersistentStoreCoordinator*)persistentStoreCoordinator
{
    @try {
        if (persistentStoreCoordinator != nil) {
            return persistentStoreCoordinator;
        }
        
        // Complete url to our database file
        NSString *databaseFilePath = [[self documentsFolder] stringByAppendingPathComponent: @"Ubira.sqlite"];
        
        // if you make changes to your model and a database already exist in the app
        // you'll get a NSInternalInconsistencyException exception. When the model i updated 
        // the databasefile must be removed.
        NSFileManager *fileManager = [NSFileManager defaultManager];
        [fileManager removeItemAtPath:databaseFilePath error:NULL];
        
        if([fileManager fileExistsAtPath:databaseFilePath])
        {
            [fileManager removeItemAtPath:databaseFilePath error:nil];
        }
        
        NSURL *storeUrl = [NSURL fileURLWithPath: databaseFilePath];
        
        NSError *error;
        persistentStoreCoordinator = [[NSPersistentStoreCoordinator alloc] initWithManagedObjectModel: [self managedObjectModel]];
        if (![persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType configuration:nil URL:storeUrl options:nil error:&error]) {
            return nil;
        }    
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        persistentStoreCoordinator = nil;
    }
	
    return persistentStoreCoordinator;
}

/*!
 @function		managedObjectContext
 @abstract		returns managed object context.
 @discussion	This fucntion will create and managed object context which is 
				attached to persistent coordinator.
 @param			none 
 @result		return NSManagedObjectContext object.
 */
- (NSManagedObjectContext*)managedObjectContext
{
    @try {
        if (managedObjectContext) return managedObjectContext;
        
        NSPersistentStoreCoordinator *coord = [self persistentStoreCoordinator];
        if (!coord) return nil;
        
        //create the managed object context and ser the persistant store co-ordinator
        managedObjectContext = [[NSManagedObjectContext alloc] init];
        [managedObjectContext setPersistentStoreCoordinator:coord];
        
        // We're not using undo. By setting it to nil we reduce the memory footprint of the app
        [managedObjectContext setUndoManager:nil];
    }
    @catch (NSException *exception) {
       TRC_EXCEPTION(exception);
        managedObjectContext = nil;
    }
	return managedObjectContext;
}


/*!
 @function		addingManagedObjectContext
 @abstract		returns managed object context for adding data to store.
 @discussion	This fucntion will create and managed object context which is 
                attached to persistent coordinator.
 @param			none 
 @result		return NSManagedObjectContext object.
 */
- (NSManagedObjectContext*)addingManagedObjectContext
{
    @try {
        if (addingManagedObjectContext) return addingManagedObjectContext;
        
        NSPersistentStoreCoordinator *coord = [self persistentStoreCoordinator];
        if (!coord) return nil;
        
        //create the managed object context and ser the persistant store co-ordinator
        addingManagedObjectContext = [[NSManagedObjectContext alloc] init];
        [addingManagedObjectContext setPersistentStoreCoordinator:coord];
        
        // We're not using undo. By setting it to nil we reduce the memory footprint of the app
        [addingManagedObjectContext setUndoManager:nil];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        addingManagedObjectContext = nil;
    }
    
	return addingManagedObjectContext;
}

/*!
 @function		commitData
 @abstract		commits data from adding context to store.
 @discussion	commits data from adding context to store
 @param			none 
 @result		return success or failure as BOOL.
 */
-(BOOL) commitData 
{
    @try {
        NSError *err = nil;
        
        if( [[self analyticsManagedObjectContext] hasChanges] )
        {
            [[self addingManagedObjectContext] save:&err];
            if( err )
            {
                TRC_ERR(@"Core Data Error : CommitData %@", [err localizedDescription] );
                return NO;
            }
        }
        return YES;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        return NO;
    }
}

/*!
 @function		addingManagedObjectContext
 @abstract		returns managed object context for adding data to store.
 @discussion	This fucntion will create and managed object context which is 
                attached to persistent coordinator.
 @param			none 
 @result		return NSManagedObjectContext object.
 */
- (NSManagedObjectContext*)analyticsManagedObjectContext
{
    @try {
        if (analyticsManagedObjectContext) return analyticsManagedObjectContext;
        
        NSPersistentStoreCoordinator *coord = [self persistentStoreCoordinator];
        if (!coord) return nil;
        
        //create the managed object context and ser the persistant store co-ordinator
        analyticsManagedObjectContext = [[NSManagedObjectContext alloc] init];
        [analyticsManagedObjectContext setPersistentStoreCoordinator:coord];
        
        // We're not using undo. By setting it to nil we reduce the memory footprint of the app
        [analyticsManagedObjectContext setUndoManager:nil];
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        analyticsManagedObjectContext = nil;
    }

	return analyticsManagedObjectContext;
}


/*!
 @function		commitAnyaliticsData
 @abstract		commits data from anyalitics context to store.
 @discussion	commits data from anyalitics context to store
 @param			none 
 @result		return success or failure as BOOL.
 */
- (BOOL)commitAnyaliticsData 
{
    @try {
        NSError *err = nil;
        
        if( [[self analyticsManagedObjectContext] hasChanges] )
        {
            [[self analyticsManagedObjectContext] save:&err];
            if( err )
            {
                TRC_ERR(@"Core Data Error : CommitData %@", [err localizedDescription] );
                return NO;
            }
        }
        
        return YES;
    }
    @catch (NSException *exception) {
        TRC_EXCEPTION(exception);
        return NO;
    }
}

@end
