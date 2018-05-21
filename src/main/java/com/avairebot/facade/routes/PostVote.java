package com.avairebot.facade.routes;

import com.avairebot.facade.Facade;
import com.avairebot.facade.contracts.spark.SparkRoute;
import okhttp3.RequestBody;
import spark.Request;
import spark.Response;

public class PostVote extends SparkRoute {

    public PostVote(Facade facade) {
        super(facade);
    }

    @Override
    public Object handle(Request request, Response response) throws Exception {
        if (request.headers("Authorization") == null) {
            return buildResponse(response, 401, "Unauthorized request, missing or invalid \"Authorization\" header give.");
        }

        okhttp3.Request req = new okhttp3.Request.Builder()
                .url(facade.getConfig().getString("avaireApi") + "/vote")
                .addHeader("Authorization", request.headers("Authorization"))
                .post(RequestBody.create(Facade.JSON, request.body()))
                .build();

        try (okhttp3.Response resp = Facade.CLIENT.newCall(req).execute()) {
            return resp.body().string();
        }
    }
}
