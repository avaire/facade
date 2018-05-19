package com.avairebot.facade.exceptions;

public class FailedToLoadPropertiesConfigurationException extends RuntimeException {

    public FailedToLoadPropertiesConfigurationException(String message, Exception exception) {
        super(message, exception);
    }
}
