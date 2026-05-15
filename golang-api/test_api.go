package main

import (
	"encoding/json"
	"fmt"
	"io/ioutil"
	"net/http"
)

func main() {
	resp, err := http.Get("http://localhost:8080/api/orders/all?limit=50")
	if err != nil {
		fmt.Println("Error:", err)
		return
	}
	defer resp.Body.Close()
	body, _ := ioutil.ReadAll(resp.Body)
	
	var data map[string]interface{}
	json.Unmarshal(body, &data)

	orders := data["data"].([]interface{})
	fmt.Printf("Total orders: %d\n", len(orders))
	
	for _, o := range orders {
		orderMap := o.(map[string]interface{})
		id := int(orderMap["id"].(float64))
		
		detailResp, err := http.Get(fmt.Sprintf("http://localhost:8080/api/orders/%d", id))
		if err != nil {
			continue
		}
		detailBody, _ := ioutil.ReadAll(detailResp.Body)
		detailResp.Body.Close()
		
		var detailData map[string]interface{}
		json.Unmarshal(detailBody, &detailData)
		
		d := detailData["data"].(map[string]interface{})
		items := d["items"].([]interface{})
		for _, it := range items {
			itemMap := it.(map[string]interface{})
			if designs, ok := itemMap["designs"]; ok {
				fmt.Printf("Order ID: %d, Item ID: %v, Designs Count: %v\n", id, itemMap["id"], len(designs.([]interface{})))
			} else {
				fmt.Printf("Order ID: %d, Item ID: %v, NO DESIGNS KEY\n", id, itemMap["id"])
			}
		}
	}
}
