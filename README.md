# dicebot
An IRC bot that rolls dice, built in php

## commands
    !help
displays help message

    !roll
rolls 1d6 (one six-sided die) and outputs the result to chat

    !roll XdY+Z
rolls XdY+Z, where `+Z` is optional (possible options include 2d8+2, 4d10, 1d20-1, etc.), and output the result to chat

## output

on a `!roll` dicebot will output results to chat in the following format

    <dicebot>: rolling 3d6+2
    <dicebot>: 5 + 6 + 3 + 2
    <dicebot>: = 16