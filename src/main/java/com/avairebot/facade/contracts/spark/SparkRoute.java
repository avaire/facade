package com.avairebot.facade.contracts.spark;

import com.avairebot.facade.Facade;
import org.json.JSONObject;
import spark.Response;
import spark.Route;

public abstract class SparkRoute implements Route {

    protected final Facade facade;

    public SparkRoute(Facade facade) {
        this.facade = facade;
    }

    protected Object buildResponse(Response response, int code, String message) {
        response.status(code);

        JSONObject root = new JSONObject();

        root.put("status", code);
        root.put(code == 200 ? "message" : "reason", message);

        return root;
    }
}