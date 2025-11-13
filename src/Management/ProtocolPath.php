<?php

namespace Yazor\MinecraftProtocol\Management;

enum ProtocolPath: string
{
    case SET = "/set";
    case ADD = "/add";
    case REMOVE = "/remove";
    case CLEAR = "/clear";
    case KICK = "/kick";
    case STATUS = "/status";
    case SAVE = "/save";
    case STOP = "/stop";
    case SYSTEM_MESSAGE = "/system_message";


}
