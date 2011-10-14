//
//  ApplicationLifeCycle.m
//  Ubira
//
//  Created by [Cybage Team] on 13/06/11.
//  Copyright 2011 FreeCause. All rights reserved.
//

#import "ApplicationLifeCycle.h"
#import "Analytics.h"


@implementation ApplicationLifeCycle
@dynamic ApplicationLifeID;
@dynamic ApplicationLifeToAnyalitics;

- (void)addApplicationLifeToAnyaliticsObject:(Analytics *)value {    
    NSSet *changedObjects = [[NSSet alloc] initWithObjects:&value count:1];
    [self willChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueUnionSetMutation usingObjects:changedObjects];
    [[self primitiveValueForKey:@"ApplicationLifeToAnyalitics"] addObject:value];
    [self didChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueUnionSetMutation usingObjects:changedObjects];
    [changedObjects release];
}

- (void)removeApplicationLifeToAnyaliticsObject:(Analytics *)value {
    NSSet *changedObjects = [[NSSet alloc] initWithObjects:&value count:1];
    [self willChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueMinusSetMutation usingObjects:changedObjects];
    [[self primitiveValueForKey:@"ApplicationLifeToAnyalitics"] removeObject:value];
    [self didChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueMinusSetMutation usingObjects:changedObjects];
    [changedObjects release];
}

- (void)addApplicationLifeToAnyalitics:(NSSet *)value {    
    [self willChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueUnionSetMutation usingObjects:value];
    [[self primitiveValueForKey:@"ApplicationLifeToAnyalitics"] unionSet:value];
    [self didChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueUnionSetMutation usingObjects:value];
}

- (void)removeApplicationLifeToAnyalitics:(NSSet *)value {
    [self willChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueMinusSetMutation usingObjects:value];
    [[self primitiveValueForKey:@"ApplicationLifeToAnyalitics"] minusSet:value];
    [self didChangeValueForKey:@"ApplicationLifeToAnyalitics" withSetMutation:NSKeyValueMinusSetMutation usingObjects:value];
}


@end
