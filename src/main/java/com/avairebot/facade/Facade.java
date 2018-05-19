package com.avairebot.facade;

import org.apache.commons.cli.CommandLine;

public class Facade {

    private final CommandLine command;

    public Facade(CommandLine command) {
        this.command = command;

        System.out.println("Hello, World!");
    }

    public static String getVersionInfo() {
        return "\n\n" +
                " _______    ___       ______     ___       _______   _______ \n" +
                "|   ____|  /   \\     /      |   /   \\     |       \\ |   ____|\n" +
                "|  |__    /  ^  \\   |  ,----'  /  ^  \\    |  .--.  ||  |__   \n" +
                "|   __|  /  /_\\  \\  |  |      /  /_\\  \\   |  |  |  ||   __|  \n" +
                "|  |    /  _____  \\ |  `----./  _____  \\  |  '--'  ||  |____ \n" +
                "|__|   /__/     \\__\\ \\______/__/     \\__\\ |_______/ |_______|\n" +
                ""
                + "\n\tVersion:       " + AppInfo.getAppInfo().getVersion()
                + "\n\tJVM:           " + System.getProperty("java.version")
                + "\n";
    }
}
