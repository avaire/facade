package com.avairebot.facade.filters;

import com.avairebot.facade.Facade;
import spark.Filter;
import spark.Request;
import spark.Response;

public class HttpFilter implements Filter {

    @Override
    public void handle(Request request, Response response) throws Exception {
        Facade.getLogger().debug(request.requestMethod() + " " + request.pathInfo());

        response.header("Access-Control-Allow-Origin", "*");
        response.type("application/json");
    }
}