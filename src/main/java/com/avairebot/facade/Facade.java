package com.avairebot.facade;

import com.avairebot.facade.config.Configuration;
import com.avairebot.shared.ExitCodes;
import org.apache.commons.cli.CommandLine;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class Facade {

    private static final Logger LOGGER = LoggerFactory.getLogger(Facade.class);

    private final CommandLine command;
    private final Configuration config;

    public Facade(CommandLine command) {
        this.command = command;

        System.out.println(getVersionInfo());

        LOGGER.debug("====================================================");
        LOGGER.debug("Starting the application with debug logging enabled!");
        LOGGER.debug("====================================================\n");

        LOGGER.info("Bootstrapping AvaIre Facade v" + AppInfo.getAppInfo().getVersion());

        config = new Configuration(this, null, "facade.yml");
        if (!config.exists()) {
            LOGGER.info("The {} configuration file is missing!", "facade.yml");
            LOGGER.info("Creating file and terminating program...");

            config.saveDefaultConfig();

            System.exit(ExitCodes.EXIT_CODE_NORMAL);
        }
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

    public static Logger getLogger() {
        return LOGGER;
    }
}
