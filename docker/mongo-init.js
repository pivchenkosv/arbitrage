db.createUser(
    {
        user: "arbitrage",
        pwd: "secret",
        roles: [
            {
                role: "readWrite",
                db: "arbitrage"
            }
        ]
    }
);
