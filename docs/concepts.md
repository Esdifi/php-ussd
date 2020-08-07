# Concepts

### USSD Gateway Provider(s)
Gateway Providers are the organisations/services that are sending the requests to your application.
Eg: Hubtel (Ghana), etc.
PHUSSD comes with 2 Gateway Providers (General, Hubtel) by default. This however can be easily be expanded to add more.
I will walk you through creatinga Gateway Provider shortly. 

Each Provider sends a request (GatewayProviderRequestContract) and expects a response (GatewayProviderResponseContract).

GatewayProviderRequestContract  
This handles the request. It validates the request and retrieves data from the request. This data is used in the
GatewayProviderProcessor to process the USSD request. After successful processing, a GatewayProviderResponse 
is returned.

GatewayProviderResponseContract
This class is responsible for formatting the response from our USSD application to match a format the USSD provider 
understands.

