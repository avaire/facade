package com.avairebot.facade;

import com.avairebot.shared.ExitCodes;
import org.apache.commons.cli.*;

public class Main {

    public static void main(String[] args) {
        Options options = new Options();

        options.addOption(new Option("h", "help", false, "Displays this help menu."));
        options.addOption(new Option("v", "version", false, "Displays the current version of the application."));

        CommandLineParser parser = new DefaultParser();
        HelpFormatter formatter = new HelpFormatter();

        try {
            CommandLine cmd = parser.parse(options, args);

            if (cmd.hasOption("help")) {
                formatter.printHelp("Help Menu", options);
                System.exit(ExitCodes.EXIT_CODE_NORMAL);
            } else if (cmd.hasOption("version")) {
                System.out.println(Facade.getVersionInfo());
                System.exit(ExitCodes.EXIT_CODE_NORMAL);
            }

            new Facade(cmd);
        } catch (ParseException e) {
            e.printStackTrace();
        }
    }
}
