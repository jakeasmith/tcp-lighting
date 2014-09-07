// lmutracker.mm
//
// clang -o lmutracker lmutracker.mm -framework IOKit -framework CoreFoundation
// http://stackoverflow.com/questions/17625495/how-do-you-programmatically-access-the-ambient-light-sensor-on-mac-os-x-10-5

#include <mach/mach.h>
#import <IOKit/IOKitLib.h>
#import <CoreFoundation/CoreFoundation.h>

static double updateInterval = 0.1;
static io_connect_t dataPort = 0;

void updateTimerCallBack(CFRunLoopTimerRef timer, void *info) {
  kern_return_t kr;
  uint32_t outputs = 2;
  uint64_t values[outputs];

  kr = IOConnectCallMethod(dataPort, 0, nil, 0, nil, 0, values, &outputs, nil, 0);
  if (kr == KERN_SUCCESS) {
    printf("\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b%8lld %8lld", values[0], values[1]);
    return;
  }

  if (kr == kIOReturnBusy) {
    return;
  }

  mach_error("I/O Kit error:", kr);
  exit(kr);
}

int main(void) {
  kern_return_t kr;
  io_service_t serviceObject;
  CFRunLoopTimerRef updateTimer;
  uint32_t outputs = 2;
  uint64_t values[outputs];

  serviceObject = IOServiceGetMatchingService(kIOMasterPortDefault, IOServiceMatching("AppleLMUController"));
  if (!serviceObject) {
    fprintf(stderr, "failed to find ambient light sensors\n");
    exit(1);
  }

  kr = IOServiceOpen(serviceObject, mach_task_self(), 0, &dataPort);
  IOObjectRelease(serviceObject);
  if (kr != KERN_SUCCESS) {
    mach_error("IOServiceOpen:", kr);
    exit(kr);
  }

  kr = IOConnectCallMethod(dataPort, 0, nil, 0, nil, 0, values, &outputs, nil, 0);
  if (kr == KERN_SUCCESS) {
    printf("%lld\n", values[0]);
    // printf("\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b\b%8lld %8lld", values[0], values[1]);
    exit(0);
  }

  // setbuf(stdout, NULL);
  // printf("%8ld %8ld", 0L, 0L);

  // updateTimer = CFRunLoopTimerCreate(kCFAllocatorDefault,
  //                 CFAbsoluteTimeGetCurrent() + updateInterval, updateInterval,
  //                 0, 0, updateTimerCallBack, NULL);
  // CFRunLoopAddTimer(CFRunLoopGetCurrent(), updateTimer, kCFRunLoopDefaultMode);
  // CFRunLoopRun();

  exit(0);
}
