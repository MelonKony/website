
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

