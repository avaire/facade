package com.avairebot.facade.contracts.spark;

import com.avairebot.facade.Facade;
import spark.Route;

public abstract class SparkRoute implements Route {

    protected final Facade facade;

    public SparkRoute(Facade facade) {
        this.facade = facade;
    }
}