package com.avairebot.facade.routes;

import com.avairebot.facade.Facade;
import com.avairebot.facade.contracts.spark.SparkRoute;
import spark.Request;
import spark.Response;

public class NotFoundRoute extends SparkRoute {

    public NotFoundRoute(Facade facade) {
        super(facade);
    }

    @Override
    public Object handle(Request request, Response response) throws Exception {
        return "{\"status\": 404, \"reason\": \"Requested route does not exists.\"}";
    }
}
