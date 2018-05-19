package com.avairebot.facade;

import com.avairebot.facade.exceptions.FailedToLoadPropertiesConfigurationException;

import java.io.IOException;
import java.util.Properties;

public class AppInfo {

    private static AppInfo INSTANCE;

    private final String version;
    private final String groupId;
    private final String artifactId;

    private AppInfo() {
        Properties properties = new Properties();
        try {
            properties.load(getClass().getClassLoader().getResourceAsStream("app.properties"));
        } catch (IOException e) {
            throw new FailedToLoadPropertiesConfigurationException("Failed to load " + "app.properties", e);
        }

        version = properties.getProperty("version");
        groupId = properties.getProperty("groupId");
        artifactId = properties.getProperty("artifactId");
    }

    public static AppInfo getAppInfo() {
        if (INSTANCE == null) {
            INSTANCE = new AppInfo();
        }
        return INSTANCE;
    }

    public String getVersion() {
        return version;
    }

    public String getGroupId() {
        return groupId;
    }

    public String getArtifactId() {
        return artifactId;
    }
}
