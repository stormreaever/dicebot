# dicebot
An IRC bot that rolls dice, built in php.

## instructions

open `dicebot.php` in a text editor and change the variables on lines `4` and `5` to the channel and server you want to connect to. Run with php.

## commands

use the following commands in chat.

    !help
displays help message.

    !roll
rolls 1d6 (one six-sided die) and outputs the result to chat.

    !roll XdY+Z
rolls `XdY+Z`, where `+Z` is optional (possible options include `2d8+2`, `4d10`, `1d20-1`, etc.), and output the result to chat.

## output

on a `!roll` dicebot will output results to chat in the following format:

    <USER>: !roll 3d6+2
    <dicebot>: rolling 3d6+2
    <dicebot>: 5 + 6 + 3 + 2
    <dicebot>: = 16

## extras

dicebot also responds when any user types its name in chat. Edit the strings in the `$messages` array on line `163` to change the random messages.

    <USER>: dicebot is a very useful bot
    <dicebot>: Did you mention me?