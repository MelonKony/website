
var falling_layers = [
    new Animated_Object("falling-1",[KeyFrame(0, 0, 455), KeyFrame(910, 0, 1150)], pre_ani = 'stop'),
    new Animated_Object("falling-2",[KeyFrame(0, 0, 455), KeyFrame(910, 0, 1100)], pre_ani = 'stop'),
    new Animated_Object("falling-3",[KeyFrame(0, 0, 455), KeyFrame(910, 0, 1000)], pre_ani = 'stop'),
    new Animated_Object("falling-4",[KeyFrame(0, 0, 455), KeyFrame(910, 0, 900)], pre_ani = 'stop'),
    new Animated_Object("falling-5",[KeyFrame(0, 0, 455), KeyFrame(282, 0, 600)], pre_ani = 'stop'),
    new Animated_Object("falling-6",[KeyFrame(0, 0, 455), KeyFrame(600, 0, 650)], pre_ani = 'stop'),
    new Animated_Object("falling-7",[KeyFrame(0, 0, 0), KeyFrame(910, 0, 0)], pre_ani = 'stop')
];
Register_Animation(new Animation('HeaderCanvas', falling_layers, 3000, 910, 0.5, 1.0, "#5AC2E7"));
Register_Animation(new Animation('FallingCanvas', falling_layers, 3000, 910, 0.5, 1.0, "#5AC2E7"));


var endt = 0;
var startt = 0;
var speed = .8;
var sideways_falling_layers = [
    new Animated_Object("falling-1",[KeyFrame(-500+startt, -400 * speed, 455), KeyFrame(510,  400* speed, 455+endt) ]),
    new Animated_Object("falling-2",[KeyFrame(-500+startt, -500 * speed, 455), KeyFrame(510,  500* speed, 455+endt) ]),
    new Animated_Object("falling-3",[KeyFrame(-500+startt, -600 * speed, 455), KeyFrame(510,  600* speed, 455+endt) ]),
    new Animated_Object("falling-4",[KeyFrame(-500+startt, -700 * speed, 455) , KeyFrame(510, 700* speed, 455+endt) ]),
    new Animated_Object("falling-5",[KeyFrame(-500+startt, -800 * speed, 455) , KeyFrame(510, 800* speed, 455+endt) ]),
    new Animated_Object("falling-6",[KeyFrame(-500+startt, -900 * speed, 455) , KeyFrame(510, 900* speed, 455+endt) ]),
    new Animated_Object("falling-7",[KeyFrame(-500+startt, -1000, 0)   , KeyFrame(510, 1000, 0+endt)   ])
];
Register_Animation(new Animation('FallingCanvas2', sideways_falling_layers, 3000, 910, 0.5, 1.0, "#5AC2E7"));

endt = 510;
startt = 0;
speed = .8;
var moving_sideways_falling_layers = [
    new Animated_Object("falling-1",[KeyFrame(startt,  -400 * speed, 455), KeyFrame(510,   400 * speed, 455+endt) ], pre_ani = 'stop'),
    new Animated_Object("falling-2",[KeyFrame(startt,  -500 * speed, 455), KeyFrame(510,   500 * speed, 455+endt) ], pre_ani = 'stop'),
    new Animated_Object("falling-3",[KeyFrame(startt,  -600 * speed, 455), KeyFrame(510,   600 * speed, 455+endt) ], pre_ani = 'stop'),
    new Animated_Object("falling-4",[KeyFrame(startt,  -700 * speed, 455) , KeyFrame(510,  700 * speed, 455+endt) ], pre_ani = 'stop'),
    new Animated_Object("falling-5",[KeyFrame(startt,  -800 * speed, 455) , KeyFrame(510,  800 * speed, 455+endt) ], pre_ani = 'stop'),
    new Animated_Object("falling-6",[KeyFrame(startt ,  -900 * speed, 455) , KeyFrame(510,  900 * speed, 455+endt)], pre_ani = 'stop'),
    new Animated_Object("tzipora",  [KeyFrame(-800, 1600, -300),  KeyFrame(0, 1300, 150), KeyFrame(250, 1400, 800), KeyFrame(450, 1600, 1600)  ]),
    new Animated_Object("falling-7v2",[KeyFrame(startt, -1000 * speed, 0)   , KeyFrame(510, 1000 * speed, 0+endt) ], pre_ani = 'stop')
    

];
Register_Animation(new Animation('FallingCanvas3', moving_sideways_falling_layers, 3000, 910, 0.5, 0.0, "#5AC2E7"));
